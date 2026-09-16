<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {return view('welcome');})->name('home');

Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::put('departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

Route::get('/login',function(){return view('Auth.Login');})->name('login');
Route::get('/register',function(){return view('Auth.register');})->name('register');
Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
Route::post('/logout', function () {Auth::logout();request()->session()->invalidate();request()->session()->regenerateToken();return redirect('/');})->name('logout');

