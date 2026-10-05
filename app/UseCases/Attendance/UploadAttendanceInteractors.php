<?php

namespace App\UseCases\Attendance;

use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class UploadAttendanceInteractors
{
    public function __construct(
        private StoreAttendanceInteractors $storeAttendanceInteractors
    ) {}

    public function execute(UploadedFile $file): void
    {
        $handle = fopen($file->getRealPath(), 'r');
        if (! $handle) {
            throw new \Exception('Could not open CSV file.');
        }

        $headers = fgetcsv($handle);
        $headers = array_map(fn ($h) => strtolower(trim($h)), $headers);
        $rowNumber = 1;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if (count($row) !== count($headers)) {
                $errors["row_{$rowNumber}"] = ['Invalid column count'];

                continue;
            }

            $data = array_combine($headers, $row);
            $employeeId = Employee::query()
                ->where('employee_id', trim($data['employee_id']))
                ->value('id');

            if ($employeeId === null) {
                $errors["row_{$rowNumber}"] = [
                    "Employee ID '{$data['employee_id']}' does not exist.",
                ];

                continue;
            }

            try {
                $this->storeAttendanceInteractors->execute([
                    'employee_id' => $employeeId,
                    'date' => $data['date'],
                    'in_time' => $data['in_time'],
                    'out_time' => $data['out_time'] ?? null,
                ]);

            } catch (ValidationException $e) {
                $errors["row_{$rowNumber}"] = $e->validator->errors()->all();
            }
        }
        fclose($handle);
        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
