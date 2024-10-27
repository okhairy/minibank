<?php
// routes/web.php
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistributeurController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:distributeur');

// Route pour l'inscription
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


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
   // Route::post('/crediter', [DistributeurController::class, 'crediterCompte']);
    Route::post('/crediter', [DistributeurController::class, 'crediterCompte'])->name('crediter');
    Route::post('/retirer', [DistributeurController::class, 'retirerCompte']);
    Route::post('/transaction/annuler/{id}', [DistributeurController::class, 'annulerTransaction'])->name('transaction.annuler');
   //Route::post('/annuler/{id}', [DistributeurController::class, 'annulerTransaction']);
    //Route::post('/transactions/annuler/{id}', [DistributeurController::class, 'annulerTransaction'])->name('transactions.annuler');

    // routes/web.php
    Route::get('/distributeur', [DistributeurController::class, 'index'])->middleware('auth');
    Route::post('/profil/update', [DistributeurController::class, 'update'])->name('profil.update');
    // routes/web.php


    Route::get('/generate-qr/{numero_compte}', [QrCodeController::class, 'generate'])->name('generate.qr');


});

// Auth routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'show']);
require __DIR__.'/auth.php';

    Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/components/transfer-modal', [HomeController::class, 'transfer'])->name('home.transfer');
    Route::get('/generate-qr/{accountNumber}', [QrCodeController::class, 'generateQrCode'])->name('generate.qr');
});
