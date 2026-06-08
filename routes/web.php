<?php

use App\Http\Controllers\ChefPoleController;
use App\Http\Controllers\MagasinierController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isMagasinier()) {
        return redirect()->route('magasinier.dashboard');
    }
    return redirect()->route('chef_pole.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Chef de Pôle routes
Route::middleware(['auth', 'role:chef_pole'])->prefix('chef-pole')->name('chef_pole.')->group(function () {
    Route::get('/dashboard', [ChefPoleController::class, 'dashboard'])->name('dashboard');
    Route::get('/demandes/creer', [ChefPoleController::class, 'createDemande'])->name('demandes.create');
    Route::post('/demandes', [ChefPoleController::class, 'storeDemande'])->name('demandes.store');
    Route::get('/historique', [ChefPoleController::class, 'history'])->name('history');
});

// Magasinier routes
Route::middleware(['auth', 'role:magasinier'])->prefix('magasinier')->name('magasinier.')->group(function () {
    Route::get('/dashboard', [MagasinierController::class, 'dashboard'])->name('dashboard');
    
    // Stock (Products CRUD)
    Route::get('/stock', [MagasinierController::class, 'stockIndex'])->name('stock.index');
    Route::get('/stock/creer', [MagasinierController::class, 'stockCreate'])->name('stock.create');
    Route::post('/stock/enregistrer', [MagasinierController::class, 'stockStore'])->name('stock.store');
    Route::get('/stock/{product}/modifier', [MagasinierController::class, 'stockEdit'])->name('stock.edit');
    Route::post('/stock/{product}/update', [MagasinierController::class, 'stockUpdate'])->name('stock.update');
    Route::delete('/stock/{product}/supprimer', [MagasinierController::class, 'stockDestroy'])->name('stock.destroy');

    // Demands Management
    Route::get('/demandes', [MagasinierController::class, 'demandesIndex'])->name('demandes.index');
    Route::get('/demandes/{demande}/approuver', [MagasinierController::class, 'demandesApprove'])->name('demandes.approve');
    Route::post('/demandes/{demande}/traiter', [MagasinierController::class, 'demandesProcess'])->name('demandes.process');
    Route::get('/historique', [MagasinierController::class, 'history'])->name('history');
});

require __DIR__.'/auth.php';
