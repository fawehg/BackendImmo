<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\AuthController;


use App\Http\Controllers\ClientController;
Route::group([
    'middleware' => 'api',
    'prefix' => 'client'
], function ($router) {

Route::post('/register', [ClientController::class, 'signup']);
Route::post('/login', [ClientController::class, 'signin']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'ouvrier'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
