<?php

namespace App\UseCases\Attendance;

use App\Jobs\ProcessAttendanceCsv;
use App\Models\AttendanceImport;
use App\Models\Employee;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class UploadAttendanceInteractors
{
    public function __construct(
        private readonly StoreAttendanceInteractors $storeAttendanceInteractors
    ) {}

    public function upload(UploadedFile $file, ?int $employeeId): AttendanceImport
    {
        $employeeId ??= Employee::query()->value('id');
        if ($employeeId === null) {
            throw ValidationException::withMessages([
                'csv_file' => 'Cannot process CSV. No employee is associated with your account.',
            ]);
        }

        $path = $file->store('temp', 'local');
        if ($path === false) {
            throw new RuntimeException('The attendance CSV could not be stored.');
        }

        $attendanceImport = AttendanceImport::query()->create([
            'employee_id' => $employeeId,
            'file_path' => $path,
        ]);

        ProcessAttendanceCsv::dispatch($attendanceImport->id, $path, $employeeId);

        return $attendanceImport;
    }

    /**
     * Process CSV from UploadedFile (Direct Upload)
     */
    public function execute(UploadedFile $file): array
    {
        $fileHandle = fopen($file->getRealPath(), 'r');
        if (! $fileHandle) {
            throw new Exception('Could not open CSV file.');
        }

        try {
            return $this->processRows($fileHandle);
        } finally {
            fclose($fileHandle);
        }
    }

    /**
     * Process CSV from a file path (Used by Background Job)
     */
    public function executeFromPath(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new Exception("CSV file not found: {$filePath}");
        }

        $fileHandle = fopen($filePath, 'r');
        if (! $fileHandle) {
            throw new Exception("Could not open CSV file: {$filePath}");
        }

        try {
            return $this->processRows($fileHandle);
        } finally {
            fclose($fileHandle);
        }
    }

    /**
     * Main Row Processing Logic
     */
    private function processRows($fileHandle): array
    {
        $headers = array_map(
            fn ($header) => strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $header))),
            fgetcsv($fileHandle)
        );

        $rows = [];
        $errors = [];
        $rowNumber = 1;
        $successCount = 0;

        while (($row = fgetcsv($fileHandle)) !== false) {
            $rowNumber++;

            $rowErrors = $this->validateAndStoreRow($row, $headers, $rowNumber);
            $csvEmployeeId = trim($row[0] ?? '');

            if (! empty($rowErrors)) {
                $errors["row_{$rowNumber}"] = $rowErrors;
                $rows[] = ['row_number' => $rowNumber, 'employee_id' => $csvEmployeeId, 'status' => 'fail', 'errors' => $rowErrors];
            } else {
                $successCount++;
                $rows[] = ['row_number' => $rowNumber, 'employee_id' => $csvEmployeeId, 'status' => 'success', 'errors' => []];
            }
        }

        $summary = sprintf('%d of %d rows failed. %d imported successfully.', count($errors), $rowNumber - 1, $successCount);

        return ['errors' => $errors, 'rows' => $rows, 'summary' => $summary];
    }

    /**
     * Validate a single row and try to store it
     */
    private function validateAndStoreRow(array $row, array $headers, int $rowNumber): array
    {
        if (count($row) !== count($headers)) {
            return ["Row {$rowNumber}: Invalid column count. Expected ".count($headers).'.'];
        }

        $data = array_combine($headers, $row);
        $csvEmployeeId = trim($data['employee_id'] ?? '');
        $errors = [];

        if (empty($csvEmployeeId)) {
            $errors[] = 'Employee ID is empty.';
        }
        if (empty(trim($data['date'] ?? ''))) {
            $errors[] = 'Date is empty.';
        }
        if (empty(trim($data['in_time'] ?? ''))) {
            $errors[] = 'In time is empty.';
        }

        $employeeId = null;
        if ($csvEmployeeId !== '') {
            $employeeId = Employee::where('employee_id', $csvEmployeeId)->value('id');
            if ($employeeId === null) {
                $errors[] = "Employee ID '{$csvEmployeeId}' does not exist.";
            }
        }

        if (! empty($errors)) {
            return array_map(fn ($err) => "Row {$rowNumber} ({$csvEmployeeId}): {$err}", $errors);
        }

        try {
            $this->storeAttendanceInteractors->execute([
                'employee_id' => $employeeId,
                'date' => Carbon::parse($data['date'])->format('Y-m-d'),
                'in_time' => Carbon::parse($data['in_time'])->format('H:i'),
                'out_time' => ! empty($data['out_time']) ? Carbon::parse($data['out_time'])->format('H:i') : null,
            ]);

            return [];
        } catch (QueryException $e) {
            return $e->getCode() === '23000'
                ? ["Row {$rowNumber} ({$csvEmployeeId}): Attendance already exists."]
                : ["Row {$rowNumber} ({$csvEmployeeId}): Database error."];
        } catch (Exception $e) {
            return ["Row {$rowNumber} ({$csvEmployeeId}): {$e->getMessage()}"];
        }
    }
}
