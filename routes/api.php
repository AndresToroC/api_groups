<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ColorController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->roles;
});

Route::prefix('auth')->group(function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function() {
    Route::apiResource('colors', ColorController::class);
});