<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // 1. Obtener solo Impresoras
        $impresoras = Product::where('category', 'Impresoras')->get();

        // 2. Obtener Destacados (Todo lo que NO sea impresora y NO sea el Creeper)
        $destacados = Product::where('category', '!=', 'Impresoras')
                             ->where('sku', '!=', 'FIG-CREEPER') // Excluimos al Creeper de la lista
                             ->get();

        return view('welcome', compact('impresoras', 'destacados'));
    }
}