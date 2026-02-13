<?php

/**
 * API routes (prefix: /api).
 * Auth: JWT (login, register, logout, me). Public: venues list/show. Admin: venue CRUD.
 */

use App\Http\Controllers\Api\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VenueController;
use Illuminate\Support\Facades\Route;

// Auth (JWT)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Public: list and show venues (no auth required)
Route::get('/venues', [VenueController::class, 'index']);
Route::get('/venues/{id}', [VenueController::class, 'show']);

// Admin: CRUD venues (JWT + admin role enforced in policy & FormRequest)
Route::middleware('auth:api')->prefix('admin')->group(function () {
    Route::post('/venues', [AdminVenueController::class, 'store']);
    Route::put('/venues/{id}', [AdminVenueController::class, 'update']);
    Route::delete('/venues/{id}', [AdminVenueController::class, 'destroy']);
});
