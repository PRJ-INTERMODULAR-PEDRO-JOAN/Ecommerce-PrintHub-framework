<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Ruta Home (usa welcome.blade.php, asegúrate de copiar el HTML de tu index.html ahí dentro)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas Públicas de Productos
Route::get('/productes', [ProductController::class, 'index'])->name('products.index');
Route::get('/productes/{id}', [ProductController::class, 'show'])->name('products.show');

// Rutas de Administración (Protegidas por Login)
Route::middleware('auth')->group(function () {
    Route::get('/importar', [ProductController::class, 'importView'])->name('import.view');
    Route::post('/importar', [ProductController::class, 'import'])->name('products.import');
    
    // Rutas de perfil (Breeze por defecto)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';