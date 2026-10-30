<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
    ];

//    public function employee()
//    {
//        return $this->belongsTo(Employee::class);
//    }

//    public function finalizedMonthlyAttendance()
//    {
//        return $this->belongsTo(MonthlyAttendanceFinalize::class, 'employee_id', 'employee_id')
//            ->where('month', $this->month)
//            ->where('year', $this->year);
//    }


}
