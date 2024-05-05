<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\EspaceProController;
use App\Http\Controllers\RechercheOuvrierController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'admin'
], function ($router) {
Route::post('/login', [EspaceProController::class, 'login']);
});
Route::get('/domaines', [DomaineController::class, 'index']);
Route::post('/domaines', [DomaineController::class, 'store']);

Route::get('/specialites', [SpecialiteController::class, 'index']);
Route::post('/specialites', [SpecialiteController::class, 'store']);




Route::group([
    'middleware' => 'api',
    'prefix' => 'client'
], function ($router) {
Route::post('/register', [ClientController::class, 'signup']);
Route::post('/login', [ClientController::class, 'signin']);
Route::post('/logout', [ClientController::class, 'logout']);
Route::post('/reset-password', [ClientController::class, 'resetPassword']);
Route::post('/verify-reset-code', [ClientController::class, 'verifyResetCode']);
Route::post('/demandes', [DemandeController::class, 'store']);
Route::get('/ouvriers', [RechercheOuvrierController::class, 'rechercherOuvriers']);
Route::post('/reserver-ouvrier', [DemandeController::class, 'selectOuvrier']);
Route::get('/validation', [DemandeController::class, 'validation']);




});

Route::group([
    'middleware' => 'api',
    'prefix' => 'ouvrier'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);
    Route::get('/profil', [AuthController::class, 'profil']);
    Route::put('/update-profil', [AuthController::class, 'mettreAJourProfil']);
    Route::get('/travail-demander', [DemandeController::class, 'travailDemander']);
    Route::post('/confirm-demande', [DemandeController::class,'confirmDemande']);


});
