<?php

/**
 * Web routes. Simple API info at /.
 */

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => ['message' => 'VenueFinder API', 'docs' => 'See /api/venues']);
