<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;

class DeleteAttendanceInteractors
{
    public function execute(string $id): void
    {
        Attendance::findOrFail($id)->delete();
    }
}
