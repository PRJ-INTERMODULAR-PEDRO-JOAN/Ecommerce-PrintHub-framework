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

// 2. LISTADO DE PRODUCTOS (Público - Para el futuro SPA)
Route::get('/products', function () {
    return response()->json(Product::all(), 200);
});

// 3. RUTAS DE COMENTARIOS (C6 - Preparación)
// Obtener comentarios de un producto
Route::get('/products/{id}/comments', [CommentController::class, 'index']);

// Publicar comentario (Protegido - Requiere token)
Route::middleware('auth:sanctum')->post('/products/{id}/comments', [CommentController::class, 'store']);