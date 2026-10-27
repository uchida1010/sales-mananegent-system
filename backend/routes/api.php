<?php

use App\Http\Controllers\MeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('password/setup', [UserController::class, 'setupPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', MeController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('roles', RoleController::class);
});
