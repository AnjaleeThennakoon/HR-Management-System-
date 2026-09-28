<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'upper_level', 'level'];

    public function upperLevel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Designation::class, 'upper_level');
    }

    public function lowerLevels(): Designation|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Designation::class, 'upper_level');
    }

    public function recalculateDescendantsLevels(): void
    {
        foreach ($this->lowerLevels as $child) {
            $child->update(['level' => $this->level + 1]);
            $child->recalculateDescendantsLevels();
        }
    }
}


