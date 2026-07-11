<?php

use App\Http\Controllers\API\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', 'me');
        Route::post('/logout', 'logout');
    });
});
