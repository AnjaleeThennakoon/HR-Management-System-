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
                fn($header) => strtolower(trim($header)),
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
            $rowErrors = $this->processRow($row, $headers);

            if (!empty($rowErrors)) {
                $errors["row_{$rowNumber}"] = $rowErrors;
            }
        }

        return $errors;
    }

    private function processRow(array $row, array $headers): array
    {
        if (count($row) !== count($headers)) {
            return ['Invalid column count'];
        }

        $rowData = array_combine($headers, $row);
        $employeeId = $this->findEmployeeId($rowData['employee_id']);

        if ($employeeId === null) {
            return ["Employee ID '{$rowData['employee_id']}' does not exist."];
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
            return $exception->validator->errors()->all();
        }
    }

    private function findEmployeeId(string $employeeId): ?int
    {
        return Employee::query()
            ->where('employee_id', trim($employeeId))
            ->value('id');
    }
}
