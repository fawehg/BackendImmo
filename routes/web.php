<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\VendeurController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\MaisonController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\EtageVillaController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\VillaController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/appartements', [AppartementController::class, 'indexe'])->name('appartements');
// Route pour la page des fermes
Route::get('/fermes', [FermeController::class, 'indexe'])->name('fermes');

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
    Route::post('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(VendeurController::class)->prefix('vendeurs')->group(function () {
            Route::get('', 'indexe')->name('vendeurs');
            Route::get('createe', 'createe')->name('vendeurs.create');
            Route::post('storee', 'storee')->name('vendeurs.store');
            Route::get('showw/{id}', 'showw')->name('vendeurs.show');
            Route::get('editt/{id}', 'editt')->name('vendeurs.edit');
            Route::put('editt/{id}', 'updatee')->name('vendeurs.update');
            Route::delete('destroyy/{id}', 'destroyy')->name('vendeurs.destroy');
    });

    Route::controller(ClientController::class)->prefix('clients')->group(function () {
        Route::get('', 'indexe')->name('clients');
        Route::get('create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('storee', 'storee')->name('clients.store');
        Route::get('showw/{id}', 'showw')->name('clients.show');
        Route::get('editt/{id}', 'editt')->name('clients.edit');
        Route::put('editt/{id}', 'updatee')->name('clients.update');
        Route::delete('destroyy/{id}', 'destroyy')->name('clients.destroy');
    });


Route::controller(ContactController::class)->prefix('contacts')->group(function () {
    Route::get('', 'index')->name('contacts');

    Route::get('show/{id}', 'show')->name('contacts.show');

});

// Route pour la page des maisons
Route::get('/maisons', [MaisonController::class, 'indexe'])->name('maisons');
Route::get('/bureaux', [BureauController::class, 'indexe'])->name('bureaux');
Route::get('/etages-villas', [EtageVillaController::class, 'indexe'])->name('etagesVillas');
Route::get('/terrains', [TerrainController::class, 'indexe'])->name('terrains');
Route::get('/villas', [VillaController::class, 'indexe'])->name('villas');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::post('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile');

    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
});
