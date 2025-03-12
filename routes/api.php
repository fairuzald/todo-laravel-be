<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\TaskController;
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

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Email verification routes
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
  ->middleware(['signed'])
  ->name('verification.verify');

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
