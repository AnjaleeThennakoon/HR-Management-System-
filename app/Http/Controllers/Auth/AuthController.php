<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\UseCases\Auth\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use LoginInteractor;
use LogoutInteractor;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.Login');
    }

    public function login(LoginRequest $request, LoginInteractor $loginInteractor): RedirectResponse
    {
        if ($loginInteractor->execute($request->toArray())) {
            $request->session()->regenerate();
            return redirect('/dashboard');
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
