<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ── Public Auth Routes (rate limited) ──────────────────────────────────
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ── Protected Routes ────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);

    // Profile
    Route::patch('/user/profile',  [AuthController::class, 'updateProfile']);
    Route::patch('/user/password', [AuthController::class, 'updatePassword']);

    // Applications — stats MUST come before {id} to avoid route collision
    Route::get('/applications/stats', [ApplicationController::class, 'stats']);

    Route::apiResource('applications', ApplicationController::class)->except(['show']);
    Route::get('/applications/{id}',    [ApplicationController::class, 'show']);
    Route::patch('/applications/{id}',  [ApplicationController::class, 'update']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);
});