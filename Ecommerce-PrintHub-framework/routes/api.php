<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. OBTENER USUARIO ACTUAL
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// 2. PRODUCTOS (Público)
Route::get('/products', function () {
    return response()->json(Product::all(), 200);
});

Route::get('/products/{id}', function ($id) {
    $product = Product::find($id);
    return $product ? response()->json($product) : response()->json(['mensaje' => 'No encontrado'], 404);
});

// 3. COMENTARIOS Y LIKES
// Ver comentarios y estado del like (Público)
Route::get('/products/{id}/comments', [CommentController::class, 'index']);
Route::get('/products/{id}/like', [LikeController::class, 'check']);

// Rutas protegidas (Requieren Login)
Route::middleware('auth:sanctum')->group(function () {
    // Comentarios
    Route::post('/products/{id}/comments', [CommentController::class, 'store']);   // Crear
    Route::put('/comments/{comment}', [CommentController::class, 'update']);       // Editar (Nuevo)
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);   // Borrar (Nuevo)

    // Likes
    Route::post('/products/{id}/like', [LikeController::class, 'toggle']);
});