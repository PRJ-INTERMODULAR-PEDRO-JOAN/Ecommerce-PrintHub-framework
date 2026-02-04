<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Página principal (Welcome)
    public function index()
    {
        // Obtenemos destacados e impresoras
        $destacados = Product::where('category', '!=', 'Impresoras')->take(4)->get();
        $impresoras = Product::where('category', 'Impresoras')->take(4)->get();

        // ✅ NUEVO: Obtenemos la oferta del día
        $ofertaDia = Product::getDailyDeal();

        return view('welcome', compact('destacados', 'impresoras', 'ofertaDia'));
    }

    // Listado completo (Catálogo)
    public function list(Request $request)
    {
        $query = Product::query();

        // Filtro por categoría
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filtro por precio
        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->price_max);
        }

        $products = $query->get();
        return view('products.index', compact('products'));
    }

    // Ver detalle de producto
    public function show($id)
    {
        $product = Product::with(['comments.user', 'likes'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    // Editar producto (Admin)
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso.');
        }
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Actualizar producto (Admin)
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Borrar imagen vieja si no es una de las default
            if ($product->image && !in_array($product->image, ['default.jpg', 'test.jpg'])) {
                 // Storage::delete('public/img/' . $product->image); (Ajustar según tu sistema de archivos)
            }
            
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('img'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('products.show', $product->id)->with('success', 'Producto actualizado correctamente');
    }
}