<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductImportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- RUTES PÚBLICAS ---
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'list'])->name('products.index'); // <--- NUEVA

// --- DASHBOARD ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTES DE PERFIL ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTES ADMIN (Importar Excel) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/import', [ProductImportController::class, 'show'])->name('admin.import');
    Route::post('/admin/import', [ProductImportController::class, 'store'])->name('admin.import.store');
});

require __DIR__.'/auth.php';