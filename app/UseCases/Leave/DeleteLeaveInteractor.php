<?php

namespace App\UseCases\Leave;

use App\Models\Leave;

class DeleteLeaveInteractor
{
    public function execute(string $id): bool
    {
        $leave = Leave::findOrFail($id);

        return $leave->delete();
    }
}
