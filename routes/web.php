<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;

// 1. RUTAS DE AUTENTICACIÓN (Login, Register...)
require __DIR__.'/auth.php';

// -----------------------------------------------------------------------------
// 2. RUTAS LEGACY (NECESARIAS PARA QUE NO FALLE EL DASHBOARD/BLADE)
// -----------------------------------------------------------------------------
// Aunque uses Vue, el Admin Panel (Blade) sigue buscando estas rutas para los menús.

// Carrito (Restaurado para corregir el error Route not defined)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Contacto y Galería (Restaurados por si hay enlaces en el menú legacy)
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contacto/exito', [ContactController::class, 'success'])->name('contact.success');
Route::get('/galeria', [GalleryController::class, 'index'])->name('gallery.index');

// Productos (Vistas públicas de Laravel)
Route::get('/productes', [ProductController::class, 'list'])->name('products.list');
Route::get('/productes/{id}', [ProductController::class, 'show'])->name('products.show');


// -----------------------------------------------------------------------------
// 3. RUTAS PROTEGIDAS (BACKOFFICE - SOLO ADMIN)
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Checkout (Proceso de compra en Laravel)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processPayment'])->name('cart.process');

    // Dashboard
    Route::get('/dashboard', function () { 
        return view('dashboard'); 
    })->name('dashboard');
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gestión de Productos (CRUD Admin)
    Route::get('/productes/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/productes/{id}', [ProductController::class, 'update'])->name('products.update');
    
    // Importación/Exportación
    Route::get('/admin/import', [ProductImportController::class, 'show'])->name('admin.import');
    Route::post('/admin/import', [ProductImportController::class, 'store'])->name('admin.import.store');
    
    Route::get('/admin/export-products', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return redirect('/export-products-agent');
    })->name('admin.export');
});

// Ruta de Exportación CSV (Pública o semi-pública)
Route::get('/export-products-agent', function () {
    $products = \App\Models\Product::all();
    $csvHeader = ["id", "title", "description", "price", "availability", "brand", "stock"];
    $callback = function() use ($products, $csvHeader) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $csvHeader);
        foreach ($products as $product) {
            $availability = $product->stock > 0 ? "In Stock" : "Out of Stock";
            $fullDescription = $product->description . ". 📦 Stock actual: " . $product->stock . " unidades.";
            fputcsv($file, [$product->id, $product->name, $fullDescription, $product->price . " EUR", $availability, 'PrintHub', $product->stock]);
        }
        fclose($file);
    };
    return Response::stream($callback, 200, ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=productos_printhub.csv"]);
});


// -----------------------------------------------------------------------------
// 4. RUTA PRINCIPAL HÍBRIDA (CATCH-ALL)
// -----------------------------------------------------------------------------
// Esta debe ir AL FINAL. Captura la ruta raíz '/' y cualquier otra no definida arriba.
Route::get('/{any?}', function () {
    $user = \Illuminate\Support\Facades\Auth::user();

    // A) Si es ADMIN -> Dashboard de Laravel
    if ($user && $user->role === 'admin') {
        return redirect()->route('dashboard');
    }

    // B) Si es CLIENTE -> Frontend Vue
    if (app()->environment('local')) {
        return redirect('http://localhost:5174');
    }
    return view('spa_view'); 

})->where('any', '^(?!api|sanctum).*$')->name('home'); // <--- ¡AQUÍ ESTÁ EL CAMBIO!