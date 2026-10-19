<?php

namespace App\Models;

use Database\Factories\LeaveApprovalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApproval extends Model
{
    /** @use HasFactory<LeaveApprovalFactory> */
    use HasFactory;

    protected $fillable = [
        'leave_id',
        'employee_id',
        'status',
        'remarks',
        'approved_at',
    ];

    public function leave(): BelongsTo
    {
        return $this->belongsTo(Leave::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
