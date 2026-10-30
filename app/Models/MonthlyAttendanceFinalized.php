<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAttendanceFinalized extends Model
{
    use HasFactory;

    protected $table = 'finalized_months';

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'total_late_minutes',
        'total_ot_minutes',
        'total_no_pay_days',
        'attendance_hash',
        'finalized_by',
        'finalized_at',
    ];

    protected $casts = [
        'finalized_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function finalizer()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
}
