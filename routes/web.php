<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DistributeurController; 

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Routes pour les clients
Route::resource('clients', ClientController::class);

Route::post('/comptes', [CompteController::class, 'store'])->name('comptes');

// Routes pour les distributeurs
Route::resource('distributeurs', DistributeurController::class);

// Routes de ressource
Route::resource('comptes', CompteController::class);

// Route pour afficher la liste des transactions
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

// Route pour effectuer un dépôt
Route::post('/deposit', [TransactionController::class, 'deposit'])->name('transactions.deposit');

// Route pour les transactions
Route::get('/transactions/search', [TransactionController::class, 'search'])->name('transactions.search');
Route::post('/transactions/{id}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
Route::post('/transactions/deposit', [TransactionController::class, 'deposit'])->name('transactions.deposit');
Route::get('/deposit', function () {
    return view('transactions.deposit'); // Renvoie la vue du formulaire de dépôt
})->name('deposit.page');
Route::post('/deposit', [TransactionController::class, 'deposit'])->name('transactions.deposit');
Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');


Route::get('/comptes/create', [CompteController::class, 'create'])->name('comptes.create');
Route::post('/comptes', [CompteController::class, 'store'])->name('comptes.store');
Route::get('/comptes/{compte}/edit', [CompteController::class, 'edit'])->name('modifier.compte');
Route::post('/comptes/{id}/bloquer', [CompteController::class, 'bloquer'])->name('bloquer.compte');
// Route::get('/comptes/bloques', [CompteController::class, 'comptes_bloques'])->name('comptes_bloques');





// Routes pour les transactions
Route::resource('transactions', TransactionController::class);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
