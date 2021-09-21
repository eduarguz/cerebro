<?php

use App\Http\Controllers\MutantController;
use App\Http\Controllers\StatsController;

Route::post('/mutant', [MutantController::class, 'store']);
Route::get('/stats', [StatsController::class, 'index']);

