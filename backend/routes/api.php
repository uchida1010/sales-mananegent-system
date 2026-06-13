<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Route::apiResource("user", UserController::class);

Route::apiResource('roles', RoleController::class);
