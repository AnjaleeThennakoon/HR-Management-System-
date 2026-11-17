<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['email', 'password', 'role', 'department_id', 'employee_id'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function getNameAttribute(?string $value = null): string
    {
        $employee = $this->employee;

        if ($employee !== null) {
            return trim($employee->first_name.' '.$employee->last_name);
        }

        return filled($value) ? $value : (string) $this->email;
    }

    public function hasApplicationRole(string $role): bool
    {
        try {
            if ($this->hasRole($role)) {
                return true;
            }
        } catch (RoleDoesNotExist) {
        }

        return ! $this->roles()->exists() && $this->role === $role;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
