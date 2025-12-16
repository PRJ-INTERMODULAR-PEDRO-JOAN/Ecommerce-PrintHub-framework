<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'apiIndex']);
Route::post('/products/{id}/comments', [ProductController::class, 'storeComment']);