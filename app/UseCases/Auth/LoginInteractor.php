<?php

namespace App\UseCases\Auth;

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

            if ($user->hasRole('admin')) {
                return '/dashboard';
            }
            return '/employee/dashboard';
        }
        return null;
    }
}
