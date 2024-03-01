<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DemandeController;

Route::post('/demandes', [DemandeController::class, 'store']);

Route::post('/signup', [ClientController::class, 'signup']);
Route::post('/signin', [ClientController::class, 'signin']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user/{id}', 'AuthController@show');
Route::put('/ouvrier/{id}', 'AuthController@update');
