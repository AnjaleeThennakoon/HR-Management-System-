<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\UseCases\Auth\LoginInteractor;
use App\UseCases\Auth\LogoutInteractor;
use App\UseCases\Auth\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.Login');
    }
    public function login(LoginRequest $loginRequest, LoginInteractor $loginInteractor): RedirectResponse
    {
        $redirectUrl = $loginInteractor->execute($loginRequest->toArray());

        if ($redirectUrl) {
            $loginRequest->session()->regenerate();

            return redirect($redirectUrl);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(LogoutInteractor $logoutInteractor): RedirectResponse
    {
        $logoutInteractor->execute();
        return redirect('/');
    }
}
