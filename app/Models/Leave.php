<?php

namespace App\Models;

use App\Models\Support\LeaveSupport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HigherOrderCollectionProxy;

class Leave extends LeaveSupport
{
    use HasFactory;

    /**
     * @var HigherOrderCollectionProxy|mixed
     */
    public mixed $employee_last_name;

    /**
     * @var HigherOrderCollectionProxy|mixed
     */
    public mixed $employee_first_name;

    protected $fillable = [
        'date',
        'reason',
        'type',
        'start_date',
        'end_date',
        'status',
        'leave_type',
        'employee_id',
        'department_id',
        'day',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
