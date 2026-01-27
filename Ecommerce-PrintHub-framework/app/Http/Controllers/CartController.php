<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];
        return view('cart.index', compact('cart', 'total'));
    }

    public function add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Inicia sesión para comprar.');
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
        return redirect()->back()->with('success', 'Producto añadido al carrito 🛒');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Producto eliminado');
    }

    public function checkout()
    {
        if (!Auth::check()) return redirect()->route('login');
        
        $cart = session()->get('cart', []);
        if(empty($cart)) return redirect()->route('cart.index');
        
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];

        return view('cart.checkout', compact('cart', 'total'));
    }

    public function processPayment(Request $request)
    {
        if (!Auth::check()) return redirect()->route('login');

        $request->validate([
            'address' => 'required|string|min:5',
            'card_number' => 'required|numeric',
        ]);

        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];

        // Crear Pedido
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pagado',
            'shipping_address' => $request->address,
            'payment_method' => 'tarjeta'
        ]);

        // Guardar Items
        foreach($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget('cart');
        
        // CAMBIO: En lugar de ir al home, vamos a la vista de éxito (Paso 3)
        return view('cart.success', compact('order'));
    }
}