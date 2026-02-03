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

    public function edit($id)
    {
        // Validación de Rol
        if (auth()->user()->role !== 'admin') {
            abort(403, 'ACCESO DENEGADO: Solo administradores.');
        }

        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Actualiza el producto en la BBDD (Solo Admin).
     */
    public function update(Request $request, $id)
    {
        // Validación de Rol
        if (auth()->user()->role !== 'admin') {
            abort(403, 'ACCESO DENEGADO.');
        }

        $product = Product::findOrFail($id);

        // Validar datos
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048' // Imagen opcional
        ]);

        // Actualizar campos
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        // Gestión de Imagen (si suben una nueva)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Guardar en public/img
            $file->move(public_path('img'), $filename);
            
            // Actualizar ruta en BD
            $product->image = $filename;
        }

        $product->save();

        return redirect()->route('products.show', $product->id)
                         ->with('success', '¡Producto actualizado correctamente!');
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