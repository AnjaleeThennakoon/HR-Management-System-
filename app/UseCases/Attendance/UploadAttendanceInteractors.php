<?php

namespace App\UseCases\Attendance;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

readonly class UploadAttendanceInteractors
{
    public function __construct(
        private StoreAttendanceInteractors $storeAttendanceInteractors
    ) {}

    public function execute(UploadedFile $file): void
    {
        $result = $this->processCsv($file);

        if (!empty($result['errors'])) {

            $flatErrors = [];
            foreach ($result['errors'] as $rowKey => $messages) {
                $flatErrors[$rowKey] = is_array($messages)
                    ? implode(' ', \Illuminate\Support\Arr::flatten($messages))
                    : (string) $messages;
            }

            session()->flash('import_summary', $result['summary']);
            session()->flash('import_rows', $result['rows']);

            throw ValidationException::withMessages($flatErrors);
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

            if (!empty($rowErrors)) {
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
            $csvEmployeeId = trim($row[0] ?? 'Unknown');

            return [
                "Row {$rowNumber} ({$csvEmployeeId}): Invalid column count. "
                . 'Expected ' . count($headers)
                . ' columns, but received ' . count($row) . '.',
            ];
        }

        $rowData = array_combine($headers, $row);

        if (!array_key_exists('employee_id', $rowData)) {
            return [
                "Row {$rowNumber}: Missing 'employee_id' column. Please check your CSV headers.",
            ];
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

        if (!empty($rowErrors)) {
            return array_map(
                fn ($error) => "Row {$rowNumber} ({$csvEmployeeId}): {$error}",
                $rowErrors
            );
        }

        try {
            $date = Carbon::parse($rowData['date'])->format('Y-m-d');
            $inTime = Carbon::parse($rowData['in_time'])->format('H:i');
            $outTime = !empty($rowData['out_time'])
                ? Carbon::parse($rowData['out_time'])->format('H:i')
                : null;

            $this->storeAttendanceInteractors->execute([
                'employee_id' => $employeeId,
                'date' => $date,
                'in_time' => $inTime,
                'out_time' => $outTime,
            ]);

            return [];
        } catch (ValidationException $exception) {
            return array_map(
                fn ($error) => "Row {$rowNumber} ({$csvEmployeeId}): {$error}",
                $exception->validator->errors()->all()
            );
        } catch (\Exception $exception) {
            return [
                "Row {$rowNumber} ({$csvEmployeeId}): {$exception->getMessage()}",
            ];
        }
    }

    private function findEmployeeId(string $employeeId): ?int
    {
        return Employee::query()
            ->where('employee_id', $employeeId)
            ->value('id');
    }
}
