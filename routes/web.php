<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

// --- RUTAS PÚBLICAS ---
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/galeria', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/productes', [ProductController::class, 'list'])->name('products.list');
Route::get('/productes/{id}', [ProductController::class, 'show'])->name('products.show');

// --- RUTAS DE CONTACTO ---
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');
// ✅ NUEVA RUTA DE ÉXITO
Route::get('/contacto/exito', [ContactController::class, 'success'])->name('contact.success');

// --- CARRITO ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// --- RUTAS PROTEGIDAS ---
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [CartController::class, 'processPayment'])->name('cart.process');
    
    Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['verified'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/admin/import', [ProductImportController::class, 'show'])->name('admin.import');
    Route::post('/admin/import', [ProductImportController::class, 'store'])->name('admin.import.store');
    
    Route::get('/productes/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/productes/{id}', [ProductController::class, 'update'])->name('products.update');
});

// --- EXPORTAR CSV CHATBOT ---
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

Route::get('/admin/export-products', function () {
    if (auth()->user()->role !== 'admin') abort(403);
    return redirect('/export-products-agent');
})->middleware(['auth'])->name('admin.export');

require __DIR__.'/auth.php';