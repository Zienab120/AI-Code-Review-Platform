<?php 

use App\Http\Controllers\API\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->controller(ReviewController::class)->prefix('review/pull-requests')->group(function () {
    Route::get('/{pullRequestID}', 'index');
    Route::get('/{pullRequestID}/{reviewID}', 'show');
    Route::post('/{pullRequestID}', 'store');
    Route::put('/{pullRequestID}/{reviewID}', 'update');
});
