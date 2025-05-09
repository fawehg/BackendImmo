<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VilleController;
use App\Http\Controllers\DelegationController;
use App\Http\Controllers\VendeurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TypeController;

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
use App\Http\Controllers\TypeTerrainController;
use App\Http\Controllers\TypeSolController;
use App\Http\Controllers\TerrainController;
Route::get('/etage-villas', [EtageVillaController::class, 'index']);
Route::get('/etage-villas/{id}', [EtageVillaController::class, 'show']);
Route::get('/terrains', [TerrainController::class, 'index']);
Route::get('/terrains/{id}', [TerrainController::class, 'show']);
Route::get('/fermes', [FermeController::class, 'index']);
Route::get('/fermes/{id}', [FermeController::class, 'show']);
Route::get('/bureaux', [BureauController::class, 'index']);
Route::get('/bureaux/{id}', [BureauController::class, 'show']);
Route::get('/villas', [VillaController::class, 'index']);
Route::get('/villas/{id}', [VillaController::class, 'show']);
Route::get('/appartements', [AppartementController::class, 'index']);
Route::get('/appartements/{id}', [AppartementController::class, 'show']);
Route::get('/maisons', [MaisonController::class, 'index']);
Route::get('/maisons/{id}', [MaisonController::class, 'show']);
Route::post('/terrains', [TerrainController::class, 'store']);

Route::get('/types-sols', [TypeSolController::class, 'index']);

Route::get('/types-terrains', [TypeTerrainController::class,'index']);

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

Route::get('/environnementapp', [EnvironnementAppController::class, 'index']);
Route::get('/environnements', [EnvironnementController::class, 'index']);

Route::middleware('auth:vendeurs')->group(function () {
    Route::post('/maisons', [MaisonController::class, 'store']);
    Route::post('/appartements', [AppartementController::class, 'store']);
});
Route::get('/categories', [CategorieController::class, 'index']);

Route::get('/types', [TypeController::class, 'index']);

Route::post('/vendeur/reset-password', [VendeurController::class, 'resetPassword']);
Route::post('/vendeur/verify-reset-code', [VendeurController::class, 'verifyResetCode']);

Route::get('/all-properties', [PropertyController::class, 'index']);

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

Route::middleware('auth:sanctum')->group(function () {
    // Récupérer toutes les annonces du vendeur
    Route::get('/vendeur/annonces', [VendeurController::class, 'index']);
    
    // Afficher une annonce spécifique
    Route::get('/vendeur/annonces/{id}', [VendeurController::class, 'show']);
    
    // Mettre à jour une annonce
    Route::put('/vendeur/annonces/{id}', [VendeurController::class, 'update']);
    
    // Supprimer une annonce
    Route::delete('/vendeur/annonces/{id}', [VendeurController::class, 'destroy']);
});
Route::get('/villas', [VillaController::class, 'index']);
Route::get('/maisons', [MaisonController::class, 'index']);
Route::get('/appartements', [AppartementController::class, 'index']);
Route::get('/villas', [VillaController::class, 'index']);
Route::get('/bureaux', [BureauController::class, 'index']);
Route::get('/fermes', [FermeController::class, 'index']);
Route::get('/terrains', [TerrainController::class, 'index']);
Route::get('/etage-villas', [EtageVillaController::class, 'index']);
Route::post('/contacts', [ContactController::class, 'store']);
    // Profil client
    Route::get('/client/profil', [ClientController::class, 'show']);
    Route::put('/client/profil', [ClientController::class, 'update']);
    Route::delete('/client/profil', [ClientController::class, 'destroy']);
