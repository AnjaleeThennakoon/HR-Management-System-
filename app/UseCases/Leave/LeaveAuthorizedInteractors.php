<?php

namespace App\UseCases\Leave;

use App\Models\Leave;

class LeaveAuthorizedInteractors
{
    public function execute(array $validated, int $userId): void
    {
        $leave = Leave::query()->findOrFail($validated['leave_id']);
        $approvedAt = in_array($validated['status'], ['approved', 'rejected']) ? now() : null;

        $leave->update([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
            'approved_at' => $approvedAt,
            'user_id' => $approvedAt === null ? null : $userId,
        ]);
    }
}
