<?php

namespace App\UseCases\Attendance;

use Illuminate\Http\UploadedFile;

class UploadAttendanceInteractors
{
    public function __construct(
        private StoreAttendanceInteractors $storeAttendanceInteractors
    ) {
    }

    public function execute(UploadedFile $file): void
    {
        $handle = fopen($file->getRealPath(), 'r');

        $headers = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);

            $this->storeAttendanceInteractors->execute([
                'employee_id' => $data['employee_id'],
                'date' => $data['date'],
                'in_time' => $data['in_time'],
                'out_time' => $data['out_time'],
            ]);
        }

        fclose($handle);
    }
}
