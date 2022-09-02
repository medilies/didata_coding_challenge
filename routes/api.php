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

    Route::controller(NodeController::class)->group(function () {
        Route::post('gaphs/{graph}', 'store')->name('nodes.create');

        Route::post('parent-node/{parent_node}/child-node/{child_node}/attach', 'attach')->name('nodes.attach');
    });

    // Route::post('', [NodeController::class, 'dettach'])->name('nodes.dettach');
});
