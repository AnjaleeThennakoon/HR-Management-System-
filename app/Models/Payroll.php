<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function MonthlyAttendanceFinalized(): BelongsTo
    {
        return $this->belongsTo(MonthlyAttendanceFinalized::class, 'employee_id', 'employee_id')
            ->where('month', $this->month)
            ->where('year', $this->year);
    }


}
