<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('/forgot-password', [\App\Http\Controllers\Auth\AuthController::class, 'forgotPassword'])
    ->name("password.forgot");

Route::post('/reset-password', [\App\Http\Controllers\Auth\AuthController::class, 'resetPassword'])
        ->name('password.reset');

Route::apiResource('users', \App\Http\Controllers\UserController::class)
    ->middleware('jwt.auth');