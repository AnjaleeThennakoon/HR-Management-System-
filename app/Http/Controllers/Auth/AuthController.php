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

    public function login(LoginRequest $request, LoginInteractor $loginInteractor): RedirectResponse
    {
        if ($loginInteractor->execute($request->toArray())) {
            $request->session()->regenerate();
            $user = auth()->user();
            if ($user->role === 'admin') {
                return redirect('/dashboard');
            }

            return redirect('/employee/dashboard');
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
