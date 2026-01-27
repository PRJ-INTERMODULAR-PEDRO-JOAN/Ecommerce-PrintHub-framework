<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\CartController; // <--- Importante
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;


// --- RUTAS PÚBLICAS ---
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/galeria', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/productes', [ProductController::class, 'list'])->name('products.list');
Route::get('/productes/{id}', [ProductController::class, 'show'])->name('products.show');

// --- CARRITO DE COMPRA (Público) ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// --- RUTAS PROTEGIDAS (Requieren Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Checkout y Pago (Solo usuarios registrados)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processPayment'])->name('cart.process');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Import
    Route::get('/admin/import', [ProductImportController::class, 'show'])->name('admin.import');
    Route::post('/admin/import', [ProductImportController::class, 'store'])->name('admin.import.store');
});

require __DIR__.'/auth.php';