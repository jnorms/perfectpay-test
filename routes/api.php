<?php

use App\Http\Controllers\V1\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'order'], function () {
    Route::post('/', [OrderController::class, 'create']);
})->as('order');