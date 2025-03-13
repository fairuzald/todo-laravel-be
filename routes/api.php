<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Email verification routes - support both formats
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
  ->name('verification.verify'); // This must be named 'verification.verify' for Laravel to find it
Route::post('/email/verify', [AuthController::class, 'verifyEmailFromRequest']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
  // Auth routes
  Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/user', [AuthController::class, 'user']);
  Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])
    ->name('verification.send');

  // Tasks routes
  Route::apiResource('tasks', TaskController::class);

  // Tags routes
  Route::apiResource('tags', TagController::class);
});
