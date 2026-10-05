<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'reason',
        'type',
        'start_date',
        'end_date',
        'status',
        'employee_id',
        'department_id',
    ];
    public function employee():BelongsTo
    {
        return $this->belongTo(Employee::class);
    }

    public function department():BelongsTo
    {
        return $this->belongTo(Department::class);
    }
}
