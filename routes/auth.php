<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use Illuminate\Support\Facades\Route;



Route::post('login', [\App\Http\Controllers\Api\Auth\UserLoginController::class, 'login']);
Route::post('logout', LogoutController::class);
