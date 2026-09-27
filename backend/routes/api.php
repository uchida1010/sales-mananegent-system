<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('user', UserController::class);

Route::post('password/setup', [UserController::class, 'setupPassword']);

Route::apiResource('roles', RoleController::class);
