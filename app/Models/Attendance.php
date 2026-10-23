<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|mixed $late_minutes
 * @property int|mixed $ot_minutes
 */
class Attendance extends Model
{
    use HasFactory;

    /**
     * @var int|mixed
     */
    protected $fillable = [
        'employee_frist_name',
        'employee_last_name',
        'employee_id',
        'date',
        'in_time',
        'out_time',
        'late_minutes',
        'ot_minutes',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
