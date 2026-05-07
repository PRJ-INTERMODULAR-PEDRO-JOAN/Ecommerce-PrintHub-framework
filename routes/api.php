<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Product;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\CheckoutController; 
use App\Http\Controllers\Api\OAuthController;
use App\Http\Controllers\Api\OrderHistoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// NOVES RUTES OAUTH GOOGLE
Route::get('/oauth/google/redirect', [OAuthController::class, 'redirectToGoogle']);
Route::get('/oauth/google/callback', [OAuthController::class, 'handleGoogleCallback']);

Route::get('/products', function () {
    return Product::all();
});
// Usamos el controlador para el detalle
Route::get('/products/{id}', [ProductController::class, 'showApi']);
Route::get('/products/{id}/comments', [CommentController::class, 'index']);

// --- RUTA DE EDICIÓN (ESENCIAL) ---
// Usamos 'web' para detectar la cookie de sesión del Admin y 'AdminMiddleware' para validar el rol
Route::put('/products/{id}', [ProductController::class, 'updateApi']);


// --- RUTAS PROTEGIDAS POR TOKEN (RESTO) ---
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/active-sessions', [AuthController::class, 'activeSessions']);
    Route::get('/user', function (Request $request) {
        return $request->user();

    });
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);    
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::put('/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    Route::post('/products/{id}/comments', [CommentController::class, 'store']);
    Route::post('/products/{id}/like', [LikeController::class, 'toggle']);
    Route::get('/products/{id}/like', [LikeController::class, 'check']);
    Route::post('/checkout', action: [CheckoutController::class, 'store']);
    Route::get('/user/orders', [OrderHistoryController::class, 'index']);

// Lista de deseos
    Route::get('/user/wishlist', [LikeController::class, 'userWishlist']);
});