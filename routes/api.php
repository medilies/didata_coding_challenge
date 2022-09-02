<?php

use App\Http\Controllers\GraphController;
use App\Http\Controllers\NodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::apiResource('graphs', GraphController::class);

    Route::post('gaphs/{graph}', [NodeController::class, 'store'])->name('nodes.create');
});
