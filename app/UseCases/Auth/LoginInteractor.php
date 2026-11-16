<?php

namespace App\UseCases\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginInteractor
{
    /**
     * @return string|null Return redirect URL if successful, null if failed
     */
    public function execute(array $credentials): ?string
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (! $user instanceof User) {
                return null;
            }

            if ($user->hasApplicationRole('admin')) {
                return '/dashboard';
            }

            return '/employee/dashboard';
        }

        return null;
    }
}
