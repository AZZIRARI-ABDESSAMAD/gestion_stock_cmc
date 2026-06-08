<?php

use App\Http\Controllers\ChefEspaceController;
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
    return redirect()->route('chef_espace.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Chef de l'espace routes
Route::middleware(['auth', 'role:chef_espace'])->prefix('chef-espace')->name('chef_espace.')->group(function () {
    Route::get('/dashboard', [ChefEspaceController::class, 'dashboard'])->name('dashboard');
    Route::get('/commandes/creer', [ChefEspaceController::class, 'createCommande'])->name('commandes.create');
    Route::post('/commandes', [ChefEspaceController::class, 'storeCommande'])->name('commandes.store');
    Route::post('/commandes/{commande}/status', [ChefEspaceController::class, 'updateStatus'])->name('commandes.status');
    Route::get('/historique', [ChefEspaceController::class, 'history'])->name('history');
});

// Magasinier routes
Route::middleware(['auth', 'role:magasinier'])->prefix('magasinier')->name('magasinier.')->group(function () {
    Route::get('/dashboard', [MagasinierController::class, 'dashboard'])->name('dashboard');
    
    // Categories CRUD
    Route::get('/categories', [MagasinierController::class, 'categoryIndex'])->name('categories.index');
    Route::get('/categories/creer', [MagasinierController::class, 'categoryCreate'])->name('categories.create');
    Route::post('/categories/enregistrer', [MagasinierController::class, 'categoryStore'])->name('categories.store');
    Route::get('/categories/{category}/modifier', [MagasinierController::class, 'categoryEdit'])->name('categories.edit');
    Route::post('/categories/{category}/update', [MagasinierController::class, 'categoryUpdate'])->name('categories.update');
    Route::delete('/categories/{category}/supprimer', [MagasinierController::class, 'categoryDestroy'])->name('categories.destroy');

    // Products CRUD (Stock)
    Route::get('/stock', [MagasinierController::class, 'stockIndex'])->name('stock.index');
    Route::get('/stock/creer', [MagasinierController::class, 'stockCreate'])->name('stock.create');
    Route::post('/stock/enregistrer', [MagasinierController::class, 'stockStore'])->name('stock.store');
    Route::get('/stock/{product}/modifier', [MagasinierController::class, 'stockEdit'])->name('stock.edit');
    Route::post('/stock/{product}/update', [MagasinierController::class, 'stockUpdate'])->name('stock.update');
    Route::delete('/stock/{product}/supprimer', [MagasinierController::class, 'stockDestroy'])->name('stock.destroy');

    // Order Management
    Route::get('/commandes', [MagasinierController::class, 'ordersIndex'])->name('orders.index');
    Route::get('/commandes/{commande}/voir', [MagasinierController::class, 'orderShow'])->name('orders.show');
    Route::post('/commandes/{commande}/traiter', [MagasinierController::class, 'orderProcess'])->name('orders.process');
    Route::post('/commandes/{commande}/status', [MagasinierController::class, 'updateStatus'])->name('orders.status');
    Route::get('/historique', [MagasinierController::class, 'globalHistory'])->name('orders.history');
});

require __DIR__.'/auth.php';
