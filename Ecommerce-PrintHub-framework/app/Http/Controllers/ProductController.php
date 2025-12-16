<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    // C5: Vista Blade pública
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // C5: API JSON per al futur Vue
    public function apiIndex()
    {
        return response()->json(Product::all());
    }

    // C4: Importar Excel
    public function import(Request $request) 
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
        //    Excel::import(new ProductsImport, $request->file('excelFile'));
            return back()->with('success', 'Productes importats correctament!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error en la importació: ' . $e->getMessage());
        }
    }
    
    // C6: Guardar comentari (API)
    public function storeComment(Request $request, $productId)
    {
        $request->validate([
            'text' => 'required|min:5',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment = Comment::create([
            //'user_id' => auth()->id() ?? 1, // 1 per defecte si no hi ha auth encara
            'product_id' => $productId,
            'text' => $request->text,
            'rating' => $request->rating
        ]);

        return response()->json(['message' => 'Comentari guardat!', 'comment' => $comment]);
    }
}