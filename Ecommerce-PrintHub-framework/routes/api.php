<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. OBTENER USUARIO ACTUAL (Protegido)
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// 2. PRODUCTOS
// A) Listado completo (ya lo tenías)
Route::get('/products', function () {
    return response()->json(Product::all(), 200);
});

// B) Obtener un solo producto por ID (NUEVO) 🔍
Route::get('/products/{id}', function ($id) {
    // Buscamos el producto
    $product = Product::find($id);

    // Si existe, lo devolvemos
    if ($product) {
        return response()->json($product, 200);
    }

    // Si no existe, devolvemos error 404
    return response()->json(['mensaje' => 'Producto no encontrado'], 404);
});

// 3. COMENTARIOS
// Ver comentarios de un producto
Route::get('/products/{id}/comments', [CommentController::class, 'index']);

// Publicar comentario (Protegido)
Route::middleware('auth:sanctum')->post('/products/{id}/comments', [CommentController::class, 'store']);