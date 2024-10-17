<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('guest')->group(function () {
    // Auth
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/about', [PageController::class, 'about'])->name('about');

    Route::get('user', function () {
        return inertia('user/UserListPage');
    });
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/', 'index');
        Route::delete('/{id}', 'destroy');
    });
});
