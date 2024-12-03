<?php

use App\Http\Controllers\Api\v1\AuthController as v1AuthController;
use App\Http\Controllers\Api\v1\CategoryController as v1CategoryController;
use App\Http\Controllers\Api\v1\ItemController as v1ItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('register', [v1AuthController::class, 'register']);
    Route::post('login', [v1AuthController::class, 'login']);

    Route::get('categories', [v1CategoryController::class, 'index']);
    
    // Routes that require authentication
    Route::middleware(['auth:api'])->group(function () {
        Route::get('profile', [v1AuthController::class, 'profile']);
        Route::get('refresh', [v1AuthController::class, 'refresh']);
        Route::post('logout', [v1AuthController::class, 'logout']);

        Route::get('items', [v1ItemController::class, 'index']);
        Route::post('items', [v1ItemController::class, 'store']);
        Route::get('items/{item}', [v1ItemController::class, 'show']);
        Route::post('items/{item}', [v1ItemController::class, 'update']);
        Route::delete('items/{item}', [v1ItemController::class, 'destroy']);
    });
});
