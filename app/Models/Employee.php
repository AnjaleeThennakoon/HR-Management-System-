<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nic',
        'phone',
        'address',
        'department_id',
        'designation_id',
    ];
}
