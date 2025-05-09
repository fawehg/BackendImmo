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
use App\Http\Controllers\FermeController;
use App\Http\Controllers\Admin\PropertyApprovalController;

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
Route::post('/properties/{type}/{id}', [PropertyApprovalController::class, 'update'])->name('properties.update');
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/properties', [PropertyApprovalController::class, 'index'])->name('properties.index');
    Route::get('/properties/{type}/{id}', [PropertyApprovalController::class, 'show'])->name('properties.show');
    Route::patch('/properties/{type}/{id}', [PropertyApprovalController::class, 'update'])->name('properties.update');
});
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(AppartementController::class)->prefix('appartements')->group(function () {
        Route::get('', 'indexappartement')->name('appartements');
        Route::get('createappartement', 'createappartement')->name('appartements.create');
        Route::post('storeappartement', 'storeappartement')->name('appartements.store');
        Route::get('showappartement/{id}', 'showappartement')->name('appartements.show');
        Route::get('editappartement/{id}', 'editappartement')->name('appartements.edit');
        Route::put('editappartement/{id}', 'updateappartement')->name('appartements.update');
        Route::delete('destroyappartement/{id}', 'destroyappartement')->name('appartements.destroy');
});
Route::controller(FermeController::class)->prefix('fermes')->group(function () {
    Route::get('', 'indexferme')->name('fermes.index');
    Route::get('createferme', 'createferme')->name('fermes.create');
    Route::post('storeferme', 'storeferme')->name('fermes.store');
    Route::get('showferme/{id}', 'showferme')->name('fermes.show');
    Route::get('editferme/{id}', 'editferme')->name('fermes.edit');
    Route::put('editferme/{id}', 'updateferme')->name('fermes.update');
    Route::delete('destroyferme/{id}', 'destroyferme')->name('fermes.destroy');
});
Route::controller(MaisonController::class)->prefix('maisons')->group(function () {
    Route::get('', 'indexmaison')->name('maisons.index');
    Route::get('createmaison', 'createmaison')->name('maisons.create');
    Route::post('storemaison', 'storemaison')->name('maisons.store');
    Route::get('showmaison/{id}', 'showmaison')->name('maisons.show');
    Route::get('editmaison/{id}', 'editmaison')->name('maisons.edit');
    Route::put('editmaison/{id}', 'updatemaison')->name('maisons.update');
    Route::delete('destroymaison/{id}', 'destroymaison')->name('maisons.destroy');
});
Route::controller(BureauController::class)->prefix('bureaux')->group(function () {
    Route::get('', 'indexbureau')->name('bureaux.index');
    Route::get('createbureau', 'createbureau')->name('bureaux.create');
    Route::post('storebureau', 'storebureau')->name('bureaux.store');
    Route::get('showbureau/{id}', 'showbureau')->name('bureaux.show');
    Route::get('editbureau/{id}', 'editbureau')->name('bureaux.edit');
    Route::put('editbureau/{id}', 'updatebureau')->name('bureaux.update');
    Route::delete('destroybureau/{id}', 'destroybureau')->name('bureaux.destroy');
});
Route::controller(EtageVillaController::class)->prefix('etagesvillas')->group(function () {
    Route::get('', 'indexetagesvillas')->name('etagesvillas.index');
    Route::get('createetagesvillas', 'createetagesvillas')->name('etagesvillas.create');
    Route::post('storeetagesvillas', 'storeetagesvillas')->name('etagesvillas.store');
    Route::get('showetagesvillas/{id}', 'showetagesvillas')->name('etagesvillas.show');
    Route::get('editetagesvillas/{id}', 'editetagesvillas')->name('etagesvillas.edit');
    Route::put('editetagesvillas/{id}', 'updateetagesvillas')->name('etagesvillas.update');
    Route::delete('destroyetagesvillas/{id}', 'destroyetagesvillas')->name('etagesvillas.destroy');
});
Route::controller(TerrainController::class)->prefix('terrains')->group(function () {
    Route::get('', 'indexterrain')->name('terrains.index');
    Route::get('createterrain', 'createterrain')->name('terrains.create');
    Route::post('storeterrain', 'storeterrain')->name('terrains.store');
    Route::get('showterrain/{id}', 'showterrain')->name('terrains.show');
    Route::get('editterrain/{id}', 'editterrain')->name('terrains.edit');
    Route::put('editterrain/{id}', 'updateterrain')->name('terrains.update');
    Route::delete('destroyterrain/{id}', 'destroyterrain')->name('terrains.destroy');
});
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
    Route::controller(VillaController::class)->prefix('villas')->group(function () {
        Route::get('', 'indexvillas')->name('etagesvillas.index');
        Route::get('createvilla', 'createvilla')->name('villas.create');
        Route::post('storevilla', 'storevilla')->name('villas.store');
        Route::get('showvilla/{id}', 'showvilla')->name('villas.show');
        Route::get('editvilla/{id}', 'editvilla')->name('villas.edit');
        Route::put('editvilla/{id}', 'updatevilla')->name('villas.update');
        Route::delete('destroyvilla/{id}', 'destroyetagesvillas')->name('villas.destroy');
    });

Route::controller(ContactController::class)->prefix('contacts')->group(function () {
    Route::get('', 'index')->name('contacts');

    Route::get('show/{id}', 'show')->name('contacts.show');

});
Route::post('/delegations/by-ville', [FermeController::class, 'getDelegationsByVille'])->name('delegations.by.ville');
// Route pour la page des maisons
Route::get('/maisons', [MaisonController::class, 'indexmaison'])->name('maisons');
Route::get('/bureaux', [BureauController::class, 'indexbureau'])->name('bureaux');
Route::get('/etagesvillas', [EtageVillaController::class, 'indexetagesvillas'])->name('etagesVillas');
Route::get('/terrains', [TerrainController::class, 'indexterrain'])->name('terrains');
Route::get('/villas', [VillaController::class, 'indexvillas'])->name('villas');
Route::get('/fermes', [FermeController::class, 'indexe'])->name('fermes');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::post('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile');

    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
});
