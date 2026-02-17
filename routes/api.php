<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Product;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS (No requieren token) ---

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // <--- IMPORTANTE

// Productos (Lectura)
Route::get('/products', function () {
    return Product::all();
});
Route::get('/products/{id}', function ($id) {
    return Product::findOrFail($id);
});
Route::get('/products/{id}/comments', [CommentController::class, 'index']);


// --- RUTAS PROTEGIDAS (Requieren Token Bearer) ---
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Perfil y Dashboard
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::put('/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Interacciones
    Route::post('/products/{id}/comments', [CommentController::class, 'store']);
    Route::post('/products/{id}/like', [LikeController::class, 'toggle']);
    Route::get('/products/{id}/like', [LikeController::class, 'check']);
});