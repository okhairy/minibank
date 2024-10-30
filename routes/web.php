<?php
// routes/web.php
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistributeurController;

//routes/agents
use App\Http\Controllers\CompteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransfertController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientsController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:distributeur');

// Route pour l'inscription
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


Route::get('/', function () {
    return redirect()->route('login');
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


  


//routes antoine

Route::match(['get', 'post'], '/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Routes pour les clients
//Route::resource('clients', ClientController::class);

Route::post('/comptes', [CompteController::class, 'store'])->name('comptes');

// Routes pour les distributeurs
//Route::resource('distributeurs', DistributeurController::class);

// Routes de ressource
Route::resource('comptes', CompteController::class);

// Route pour afficher la liste des transactions
Route::get('/transactions', [TransfertController::class, 'index'])->name('transactions.index');

// Route pour effectuer un dépôt
Route::post('/deposit', [TransfertController::class, 'deposit'])->name('transactions.deposit');

// Route pour les transactions
Route::get('/transactions/search', [TransfertController::class, 'search'])->name('transactions.search');
Route::post('/transactions/{id}/cancel', [TransfertController::class, 'cancel'])->name('transactions.cancel');
Route::post('/transactions/deposit', [TransfertController::class, 'deposit'])->name('transactions.deposit');
Route::get('/deposit', function () {
    return view('transactions.deposit'); // Renvoie la vue du formulaire de dépôt
})->name('deposit.page');
Route::post('/deposit', [TransfertController::class, 'deposit'])->name('transactions.deposit');
Route::get('/transactions/export', [TransfertController::class, 'export'])->name('transactions.export');


Route::get('/comptes/create', [CompteController::class, 'create'])->name('comptes.create');
Route::post('/comptes', [CompteController::class, 'store'])->name('comptes.store');
Route::get('/comptes/{compte}/edit', [CompteController::class, 'edit'])->name('modifier.compte');
Route::post('/comptes/{id}/bloquer', [CompteController::class, 'bloquer'])->name('bloquer.compte');
// Route::get('/comptes/bloques', [CompteController::class, 'comptes_bloques'])->name('comptes_bloques');

// Routes pour les transactions
Route::resource('transactions', TransfertController::class);
// routes/clients
//Route::get('/clients/dashboard', [ClientsController::class, 'dashboard'])->name('clients.dashboard');
Route::get('/clients/dashboard', [ClientsController::class, 'dashboard'])->name('clients.dashboard');


 Route::post('/clients/dashboard', [ClientsController::class, 'transfer'])->name('clients.transfer');
 Route::get('/qr-code', [VotreControleur::class, 'generateQrCode']);



 





require __DIR__.'/auth.php';
