<?php

use App\Http\Controllers\API\PullRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->controller(PullRequestController::class)->prefix('pull-requests')->group(function () {
    Route::get('/{owner}/{repo}', 'index');
    Route::get('/{owner}/{repo}/{number}', 'show');
    Route::post('/{owner}/{repo}/{number}', 'review');
    Route::post('/{owner}/{repo}/{number}/retry', 'retry');
    Route::post('/{owner}/{repo}/{number}/cancel', 'cancel');
});
