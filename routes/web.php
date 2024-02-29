<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\AuthController;


use App\Http\Controllers\ClientController;

Route::post('/signup', [ClientController::class, 'signup']);
Route::post('/signin', [ClientController::class, 'signin']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
