<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'upper_level', 'level'];

    public function upperLevel(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'upper_level');
    }

    public function lowerLevels(): Designation|HasMany
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
