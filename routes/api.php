<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NASAController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/odiac-data', [NASAController::class, 'getOdiacData']);
Route::get('/heatmap-data', [NASAController::class, 'getHeatmapData']);

