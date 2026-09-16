<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/home', function () {
    return redirect('/dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {return view('Auth.register');})->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
});
