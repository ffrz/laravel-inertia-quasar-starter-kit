<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('HomePage');
});

Route::get('/about', function () {
    return inertia('AboutPage');
});

Route::get('/login', function () {
    return inertia('auth/LoginPage');
});

Route::get('/register', function () {
    return inertia('auth/RegisterPage');
});


