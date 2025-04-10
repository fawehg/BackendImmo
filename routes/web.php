<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::controller(AuthController::class)->prefix('ouvriers')->group(function () {
        Route::get('', 'index')->name('ouvriers');
        Route::get('create', 'create')->name('ouvriers.create');
        Route::post('store', 'store')->name('ouvriers.store');
        Route::get('show/{id}', 'show')->name('ouvriers.show');
        Route::get('edit/{id}', 'edit')->name('ouvriers.edit');
        Route::put('edit/{id}', 'update')->name('ouvriers.update');
        Route::delete('destroy/{id}', 'destroy')->name('ouvriers.destroy');
    });

    Route::controller(ClientController::class)->prefix('clients')->group(function () {
        Route::get('', 'index')->name('clients');
        Route::get('create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('store', 'store')->name('clients.store');
        Route::get('show/{id}', 'show')->name('clients.show');
        Route::get('edit/{id}', 'edit')->name('clients.edit');
        Route::put('edit/{id}', 'update')->name('clients.update');
        Route::delete('destroy/{id}', 'destroy')->name('clients.destroy');
    });

Route::controller(DemandeController::class)->prefix('demandes')->group(function () {
    Route::get('',  'index')->name('demandes');
    Route::get('create', 'create')->name('demandes.create');
    Route::post('store' , 'store')->name('demandes.store');
    Route::get('show/{id}', 'show')->name('demandes.show');
    Route::get('edit/{id}', 'edit')->name('demandes.edit');
    Route::put('update/{id}', 'update')->name('demandes.update');
    Route::delete('destroy/{id}',  'destroy')->name('demandes.destroy');
});
Route::controller(ContactController::class)->prefix('contacts')->group(function () {
    Route::get('', 'index')->name('contacts');
    Route::get('show/{id}', 'show')->name('contacts.show');

});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::post('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile');

    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
});
