<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistributeurController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour le profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes pour les transactions des distributeurs
Route::middleware('auth')->group(function () {
    Route::post('/crediter', [DistributeurController::class, 'crediterCompte']);
    Route::post('/retirer', [DistributeurController::class, 'retirerCompte']);
    Route::post('/annuler/{id}', [DistributeurController::class, 'annulerTransaction']);
    // routes/web.php
    Route::get('/distributeur', [DistributeurController::class, 'index'])->middleware('auth');

});

// Auth routes
require __DIR__.'/auth.php';

