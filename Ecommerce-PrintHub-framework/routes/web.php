<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. PÁGINA DE INICIO (Catálogo)
Route::get('/', [ProductController::class, 'index'])->name('home');

// 2. DASHBOARD (Panel de usuario - Solo autenticados)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. RUTAS DE PERFIL (Editar nombre, contraseña, borrar cuenta)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. RUTA DE IMPORTACIÓN (SOLO PARA ADMINS)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/import', function () {
        // Verificamos si es admin. Si no, error 403 (Prohibido)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado. Solo administradores.');
        }
        return view('admin.import'); // Vista que crearemos luego
    })->name('admin.import');
});

// Rutas de autenticación (Login, Register, Logout)
require __DIR__.'/auth.php';