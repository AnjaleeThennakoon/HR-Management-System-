<?php

namespace App\UseCases\Attendance;

use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

readonly class UploadAttendanceInteractors
{
    public function __construct(
        private StoreAttendanceInteractors $storeAttendanceInteractors
    ) {}

    /**
     * @throws \Exception
     * @throws ValidationException
     */
    public function execute(UploadedFile $file): void
    {
        $errors = $this->processCsv($file);

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function processCsv(UploadedFile $file): array
    {
        $fileHandle = fopen($file->getRealPath(), 'r');

        if (!$fileHandle) {
            throw new \Exception('Could not open CSV file.');
        }

        try {
            $headers = array_map(
                fn ($header) => strtolower(trim($header)),
                fgetcsv($fileHandle)
            );

            return $this->processRows($fileHandle, $headers);
        } finally {
            fclose($fileHandle);
        }
    }

    private function processRows($fileHandle, array $headers): array
    {
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($fileHandle)) !== false) {
            $rowNumber++;

            $rowErrors = $this->processRow(
                $row,
                $headers,
                $rowNumber
            );

            if (!empty($rowErrors)) {
                $errors["row_{$rowNumber}"] = $rowErrors;
            }
        }
        return $errors;
    }

    private function processRow(
        array $row,
        array $headers,
        int $rowNumber
    ): array {
        if (count($row) !== count($headers)) {
            $csvEmployeeId = trim($row[0] ?? 'Unknown');

            return [
                "Row {$rowNumber} ({$csvEmployeeId}): Invalid column count. "
                . "Expected " . count($headers)
                . " columns, but received " . count($row) . "."
            ];
        }

        $rowData = array_combine($headers, $row);

        $csvEmployeeId = trim($rowData['employee_id']);

        $employeeId = $this->findEmployeeId($csvEmployeeId);

        if ($employeeId === null) {
            return [
                "Row {$rowNumber} ({$csvEmployeeId}): Employee ID does not exist."
            ];
        }

        try {
            $this->storeAttendanceInteractors->execute([
                'employee_id' => $employeeId,
                'date' => $rowData['date'],
                'in_time' => $rowData['in_time'],
                'out_time' => $rowData['out_time'] ?? null,
            ]);

            return [];
        } catch (ValidationException $exception) {
            return array_map(
                fn ($error) =>
                "Row {$rowNumber} ({$csvEmployeeId}): {$error}",
                $exception->validator->errors()->all()
            );
        }
    }

    private function findEmployeeId(string $employeeId): ?int
    {
        return Employee::query()
            ->where('employee_id', $employeeId)
            ->value('id');
    }
}
