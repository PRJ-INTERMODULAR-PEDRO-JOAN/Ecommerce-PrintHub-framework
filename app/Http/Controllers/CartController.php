<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Ver el carrito
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];
        return view('cart.index', compact('cart', 'total'));
    }

    // Añadir producto
    public function add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Inicia sesión para añadir al carrito.');
        }

        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Producto añadido correctamente ✅');
    }

    // Eliminar producto
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Producto eliminado');
    }

    // Pantalla de Checkout (Carga la vista con los pasos)
    public function checkout()
    {
        if (!Auth::check()) return redirect()->route('login');
        
        $cart = session()->get('cart', []);
        if(empty($cart)) return redirect()->route('cart.index');
        
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];

        return view('cart.checkout', compact('cart', 'total'));
    }

    // PROCESAR PAGO REAL
    public function processPayment(Request $request)
    {
        if (!Auth::check()) return redirect()->route('login');

        // Validamos todos los campos separados
        $request->validate([
            'street' => 'required|string|min:5',
            'city' => 'required|string|min:2',
            'zip' => 'required|string|min:4',
            'country' => 'required|string',
            'card_number' => 'required|numeric',
        ]);

        $cart = session()->get('cart', []);
        
        // Recalcular total
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];

        // 1. UNIR DIRECCIÓN: Juntamos las celdas en un solo string para la BD
        $fullAddress = $request->street . ', ' . $request->city . ' (' . $request->zip . ') - ' . $request->country;

        // 2. CREAR PEDIDO
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pagado',
            'shipping_address' => $fullAddress,
            'payment_method' => 'tarjeta'
        ]);

        // 3. GUARDAR PRODUCTOS
        foreach($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // 4. LIMPIAR Y REDIRIGIR A ÉXITO
        session()->forget('cart');
        return view('cart.success', compact('order'));
    }
}