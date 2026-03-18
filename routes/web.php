<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Tu catálogo como página de inicio
Route::get('/', [ProductController::class, 'catalog'])->name('catalog');

// API para obtener equipos por competición (AJAX)
Route::get('/api/teams/{competition}', [ProductController::class, 'getTeamsByCompetition'])->name('api.teams');

/*
|--------------------------------------------------------------------------
| Rutas de Administración (Protegidas por Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Rutas de gestión de productos
    Route::get('/products', [ProductController::class, 'productList'])->name('products.list');
    Route::get('/products/create', [ProductController::class, 'productCreate'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'productEdit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/product-images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::resource('orders', OrderController::class);
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::delete('/inventory/{inventory}', [App\Http\Controllers\InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::delete('/sales/{sale}', [App\Http\Controllers\SaleController::class, 'destroy'])->name('sales.destroy');

    // Rutas del Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/dashboard', function () {
    return redirect()->route('products.list');
    })->name('dashboard');
});

require __DIR__.'/auth.php';