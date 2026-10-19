<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceImport extends Model
{
    protected $fillable = [
        'employee_id',
        'file_path',
        'status',
        'summary',
        'rows',
        'error_message',
    ];

    protected $casts = [
        'rows' => 'array',
        'summary' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
