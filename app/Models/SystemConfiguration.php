<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'array'];

    public static function getLeaveCount(string $leaveType): int
    {
        $config = self::where('key', 'leave')->first();

        if (! $config || ! is_array($config->value)) {
            return 0;
        }

        $leaveCounts = array_change_key_case($config->value, CASE_LOWER);

        return (int) ($leaveCounts[strtolower($leaveType)] ?? 0);
    }
}
