<?php

namespace App\Models;

use Database\Factories\DesignationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level'];

    private const LEVEL_MAP = [
        'Chief Executive Officer'      => 1,
        'Chief Technology Officer'     => 2,
        'General Manager'              => 3,
        'Human Resources Manager'      => 4,
        'Finance Manager'              => 5,
        'Project Manager'              => 6,
        'Senior Software Engineer'     => 7,
        'Software Engineer'            => 8,
        'Junior Software Engineer'     => 9,
        'Software Engineering Intern'  => 10,
    ];

    protected static function booted()
    {
        static::creating(function ($designation) {
            if (empty($designation->level) && isset(self::LEVEL_MAP[$designation->name])) {
                $designation->level = self::LEVEL_MAP[$designation->name];
            }
        });
    }
}
