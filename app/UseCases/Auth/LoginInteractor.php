<?php

namespace App\UseCases\Auth;

use Illuminate\Support\Facades\Auth;

class LoginInteractor
{
    public function execute(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }
}
