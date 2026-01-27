<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $impresoras = Product::where('category', 'Impresoras')->get();
        $destacados = Product::where('category', '!=', 'Impresoras')
                             ->where('sku', '!=', 'FIG-CREEPER')->get();
        return view('welcome', compact('impresoras', 'destacados'));
    }

    public function list()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // NOU: Veure detall d'un producte
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}