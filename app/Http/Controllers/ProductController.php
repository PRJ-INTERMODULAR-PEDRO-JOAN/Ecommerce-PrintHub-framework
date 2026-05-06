<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * @group Productos
 *
 * APIs para gestionar el catálogo y detalles de productos.
 */
class ProductController extends Controller
{
    // Página principal (Welcome) [Ruta Web]
    public function index()
    {
        $destacados = Product::where('category', '!=', 'Impresoras')->take(4)->get();
        $impresoras = Product::where('category', 'Impresoras')->take(4)->get();
        $ofertaDia = Product::getDailyDeal();

        return view('welcome', compact('destacados', 'impresoras', 'ofertaDia'));
    }

    // Listado completo (Catálogo) [Ruta Web]
    public function list(Request $request)
    {
        $query = Product::query();

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->price_max);
        }

        $products = $query->get();
        return view('products.index', compact('products'));
    }

    /**
     * Obtener detalles del producto (API)
     *
     * Devuelve toda la información de un producto, incluyendo sus comentarios, número de likes y si tiene aplicado un descuento (Oferta del Día). Es público.
     * * @urlParam id int required El ID del producto. Example: 1
     */
    public function showApi($id)
    {
        $product = Product::with(['comments.user', 'likes'])->findOrFail($id);
        $ofertaDia = Product::getDailyDeal();

        // Verificamos si este producto es la oferta activa
        $product->is_daily_deal = ($ofertaDia && $ofertaDia->id == $product->id);
        
        if ($product->is_daily_deal) {
            // Aplicamos un 20% de descuento (ajustar según sea necesario)
            $product->discounted_price = round($product->price * 0.8, 2);
        }

        return response()->json($product);
    }

    // [Ruta Web]
    public function show($id)
    {
        $product = Product::with(['comments.user', 'likes'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    // [Ruta Web]
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'No tienes permiso.');
        }
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Actualizar producto (API)
     *
     * Modifica los datos principales (nombre, precio, stock...) de un producto. Requiere permisos de administrador.
     *
     * @authenticated
     * @urlParam id int required El ID del producto a modificar. Example: 1
     * @bodyParam name string required El nuevo nombre. Example: Impresora 3D Elegoo Neptune
     * @bodyParam description string required La nueva descripción del producto. Example: Impresora ultrarápida...
     * @bodyParam price float required El nuevo precio. Example: 249.99
     * @bodyParam stock int required Unidades en inventario. Example: 15
     */
    public function updateApi(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        try {
            $product = Product::findOrFail($id);
            $product->update($request->only(['name', 'description', 'price', 'stock']));

            return response()->json([
                'status' => 'success',
                'message' => 'Producto actualizado correctamente',
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    // [Ruta Web]
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
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);
        return redirect()->route('products.show', $product->id)->with('success', 'Producto actualizado correctamente');
    }
}