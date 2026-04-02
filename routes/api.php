<?php

use App\Http\Controllers\Api\RessourceController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware('throttle:60,1')->group(function () {
    Route::get('/ressources', [RessourceController::class, 'index']);
    Route::get('/ressources/{id}', [RessourceController::class, 'show']);
});
