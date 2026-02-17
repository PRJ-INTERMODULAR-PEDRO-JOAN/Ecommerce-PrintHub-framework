<?php

use App\Http\Controllers\Api\AuthController;
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

// --- RUTAS DE AUTENTICACIÓN (LOGIN/LOGOUT) ---
// IMPORTANTE: Usamos el middleware 'web' para permitir sesiones y cookies
Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas que requieren que el usuario esté identificado
Route::middleware(['web', 'auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
// --- OBTENER USUARIO ACTUAL ---
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// --- PRODUCTOS (Público) ---
Route::get('/products', function () {
    return response()->json(Product::all(), 200);
});

Route::get('/products/{id}', function ($id) {
    $product = Product::find($id);
    return $product ? response()->json($product) : response()->json(['mensaje' => 'No encontrado'], 404);
});

// --- COMENTARIOS Y LIKES (Público) ---
Route::get('/products/{id}/comments', [CommentController::class, 'index']);
Route::get('/products/{id}/like', [LikeController::class, 'check']);

// --- RUTAS PROTEGIDAS (Requieren Login) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/{id}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/products/{id}/like', [LikeController::class, 'toggle']);
});