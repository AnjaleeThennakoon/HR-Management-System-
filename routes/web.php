<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {return view('welcome');})->name('home');

Route::get('/login', function () {return view('Auth.Login');})->name('login')->middleware('guest');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {request()->session()->regenerate();return redirect('/dashboard');}

    return back()->withErrors(['email' => 'Invalid email or password.',])->onlyInput('email');});

Route::get('/register', function () {return view('Auth.register');})->name('register');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
});
