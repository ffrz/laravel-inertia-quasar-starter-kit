<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register')
    ->middleware('guest');

Route::post('register', [RegisteredUserController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

Route::get('/about', function () {
    return inertia('AboutPage');
})->middleware('auth');


// Route::get('/register', function () {
//     return inertia('auth/RegisterPage');
// });
