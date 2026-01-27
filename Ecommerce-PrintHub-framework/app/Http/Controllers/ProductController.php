<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Para la página de inicio (Home)
    public function index()
    {
        $impresoras = Product::where('category', 'Impresoras')->get();
        $destacados = Product::where('category', '!=', 'Impresoras')
                             ->where('sku', '!=', 'FIG-CREEPER')
                             ->get();

        return view('welcome', compact('impresoras', 'destacados'));
    }

    // NUEVO: Para el catálogo completo (/productes)
    public function list()
    {
        // Obtenemos todos los productos ordenados
        $products = Product::all();
        
        return view('products.index', compact('products'));
    }
}