<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Muestra la lista de productos (ruta: /productes)
    public function index()
    {
        // Obtiene todos los productos paginados (o usa all() si son pocos)
        $products = Product::paginate(12);
        
        // Asegúrate de crear la vista en resources/views/products/index.blade.php
        return view('products.index', compact('products'));
    }

    // Importar productos (ruta: POST /products/import)
    public function import(Request $request)
    {
        // Validación básica
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx',
        ]);

        // AQUI IRÍA TU LÓGICA DE IMPORTACIÓN
        // Por ejemplo, usando una librería como Laravel Excel o leyendo el CSV manualmente.
        
        return back()->with('success', 'Productos importados correctamente.');
    }
}