<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceImport extends Model
{
    protected $fillable = [
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
