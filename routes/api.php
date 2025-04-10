<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VilleController;
use App\Http\Controllers\DelegationController;
use App\Http\Controllers\VendeurController;
use App\Http\Controllers\ClientController;

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MaisonController;
use App\Http\Controllers\EnvironnementController;
use App\Http\Controllers\EnvironnementAppController ;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\CaracteristiqueBureauController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\EnvironnementFermeController;

use App\Http\Controllers\InfrastructureFermesController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\EtageVillaController;

Route::post('/etage-villas', [EtageVillaController::class, 'store']);
Route::post('/fermes', [FermeController::class,'store']);

Route::get('/infrastructures', [InfrastructureFermesController::class, 'index']);
use App\Http\Controllers\OrientationFermesController;

Route::get('/orientations', [OrientationFermesController::class, 'index']);

Route::get('environnement-fermes', [EnvironnementFermeController::class, 'index']);
Route::post('/bureaux', [BureauController::class,'store']);
Route::get('/caracteristique-bureaux',[CaracteristiqueBureauController::class,'index']);
Route::post('/villas', [VillaController::class, 'store']);
Route::get('/villas', [VillaController::class, 'index']);

Route::post('/appartements', [AppartementController::class, 'store']);
Route::get('/environnementapp', [EnvironnementAppController::class, 'index']);
Route::get('/environnements', [EnvironnementController::class, 'index']);

Route::post('/maisons', [MaisonController::class, 'store']);

Route::get('/categories', [CategorieController::class, 'index']);

use App\Http\Controllers\TypeController;
Route::get('/types', [TypeController::class, 'index']);

Route::post('/vendeur/reset-password', [VendeurController::class, 'resetPassword']);
Route::post('/vendeur/verify-reset-code', [VendeurController::class, 'verifyResetCode']);

Route::post('/reset-password', [ClientController::class, 'resetPassword']);
Route::post('/verify-reset-code', [ClientController::class, 'verifyResetCode']);

Route::post('/registerclient', [ClientController::class, 'register']);
Route::post('/loginclient', [ClientController::class, 'login']);

Route::middleware('auth:clients')->group(function () {
    Route::get('/me', [ClientController::class, 'me']);
    Route::post('/logoutclient', [ClientController::class, 'logout']);
});
Route::post('/registervendeur', [VendeurController::class, 'register']);
Route::post('/loginvendeur', [VendeurController::class, 'login']);
Route::get('/delegations/{villeId}', [DelegationController::class, 'getDelegationsByVille']);
Route::get('/villes', [VilleController::class, 'index']);


Route::get('/vendeur/profil', [VendeurController::class, 'show']);
Route::put('/vendeur/profil', [VendeurController::class, 'update']);
Route::delete('/vendeur/profil', [VendeurController::class, 'destroy']);




Route::post('/contacts', [ContactController::class, 'store']);
    // Profil client
    Route::get('/client/profil', [ClientController::class, 'show']);
    Route::put('/client/profil', [ClientController::class, 'update']);
    Route::delete('/client/profil', [ClientController::class, 'destroy']);
