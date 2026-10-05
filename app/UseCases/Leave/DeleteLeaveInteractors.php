<?php

namespace App\UseCases\Leave;

use App\Models\Leave;

class DeleteLeaveInteractors
{
    public function execute(string $id): bool
    {
        $leave = Leave::findOrFail($id);

        return $leave->delete();
    }
}
