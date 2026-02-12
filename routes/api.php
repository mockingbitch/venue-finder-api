<?php

use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\QuoteRequestController;
use App\Http\Controllers\Api\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/venues', [VenueController::class, 'index']);
Route::get('/venues/map', [VenueController::class, 'map']);
Route::get('/venues/{slug}', [VenueController::class, 'show']);

Route::get('/favorites', [FavoriteController::class, 'index']);
Route::post('/favorites/{venue}/toggle', [FavoriteController::class, 'toggle']);

Route::post('/quote-requests', [QuoteRequestController::class, 'store']);
