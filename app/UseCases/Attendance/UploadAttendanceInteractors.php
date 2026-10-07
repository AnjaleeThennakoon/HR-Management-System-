<?php

namespace App\UseCases\Attendance;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

readonly class UploadAttendanceInteractors
{
    public function __construct(
        private StoreAttendanceInteractors $storeAttendanceInteractors
    ) {}

    public function execute(UploadedFile $file): void
    {
        $result = $this->processCsv($file);

        if (! empty($result['errors'])) {
            $rowErrors = array_map(
                fn (array $messages): string => implode(' ', $messages),
                $result['errors']
            );

            throw ValidationException::withMessages([
                'import_summary' => [$result['summary']],
                'import_rows' => [json_encode($result['rows'])],
                ...$rowErrors,
            ]);
        }
    }

    private function processCsv(UploadedFile $file): array
    {
        $fileHandle = fopen($file->getRealPath(), 'r');

        if (! $fileHandle) {
            throw new \Exception('Could not open CSV file.');
        }

        try {
            $headers = array_map(
                fn ($header) => strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $header))),
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
        $rows = [];
        $rowNumber = 1;
        $totalRows = 0;
        $successCount = 0;

        while (($row = fgetcsv($fileHandle)) !== false) {
            $rowNumber++;
            $totalRows++;

            $rowErrors = $this->processRow($row, $headers, $rowNumber);
            $csvEmployeeId = trim($row[0] ?? '');

            if (! empty($rowErrors)) {
                $errors["row_{$rowNumber}"] = $rowErrors;
                $rows[] = [
                    'row_number' => $rowNumber,
                    'employee_id' => $csvEmployeeId,
                    'status' => 'fail',
                    'errors' => $rowErrors,
                ];
            } else {
                $successCount++;
                $rows[] = [
                    'row_number' => $rowNumber,
                    'employee_id' => $csvEmployeeId,
                    'status' => 'success',
                    'errors' => [],
                ];
            }
        }

        $summary = sprintf(
            '%d of %d rows failed. %d imported successfully.',
            count($errors),
            $totalRows,
            $successCount
        );

        return [
            'errors' => $errors,
            'rows' => $rows,
            'summary' => $summary,
        ];
    }

    private function processRow(array $row, array $headers, int $rowNumber): array
    {
        if (count($row) !== count($headers)) {
            return [
                "Row {$rowNumber} (".trim($row[0] ?? '').'): Invalid column count. Expected '.count($headers).' columns, but received '.count($row).'.',
            ];
        }

        $rowData = array_combine($headers, $row);

        if (! array_key_exists('employee_id', $rowData)) {
            return ["Row {$rowNumber}: Missing 'employee_id' column."];
        }

        $rowErrors = [];
        $csvEmployeeId = trim($rowData['employee_id'] ?? '');

        if ($csvEmployeeId === '') {
            $rowErrors[] = 'Employee ID is empty.';
        }
        if (empty(trim($rowData['date'] ?? ''))) {
            $rowErrors[] = 'Date is empty.';
        }
        if (empty(trim($rowData['in_time'] ?? ''))) {
            $rowErrors[] = 'In time is empty.';
        }

        $employeeId = null;
        if ($csvEmployeeId !== '') {
            $employeeId = $this->findEmployeeId($csvEmployeeId);
            if ($employeeId === null) {
                $rowErrors[] = "Employee ID '{$csvEmployeeId}' does not exist.";
            }
        }

        if (! empty($rowErrors)) {
            return array_map(
                fn ($error) => "Row {$rowNumber} ({$csvEmployeeId}): {$error}",
                $rowErrors
            );
        }

        try {
            $this->storeAttendanceInteractors->execute([
                'employee_id' => $employeeId,
                'date' => Carbon::parse($rowData['date'])->format('Y-m-d'),
                'in_time' => Carbon::parse($rowData['in_time'])->format('H:i'),
                'out_time' => ! empty($rowData['out_time']) ? Carbon::parse($rowData['out_time'])->format('H:i') : null,
            ]);

            return [];
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                return ["Row {$rowNumber} ({$csvEmployeeId}): Attendance already exists for this employee on this date."];
            }

            return ["Row {$rowNumber} ({$csvEmployeeId}): Database error. Please try again."];
        } catch (\Exception $exception) {
            return ["Row {$rowNumber} ({$csvEmployeeId}): {$exception->getMessage()}"];
        }
    }

    private function findEmployeeId(string $employeeId): ?int
    {
        return Employee::query()->where('employee_id', $employeeId)->value('id');
    }
}
