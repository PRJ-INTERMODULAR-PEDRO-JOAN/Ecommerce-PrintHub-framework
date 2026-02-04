<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];
        return view('cart.index', compact('cart', 'total'));
    }

    // ✅ FUNCIÓN MODIFICADA: Aplica el 50% si es oferta
    public function add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Inicia sesión para añadir al carrito.');
        }

        $product = Product::findOrFail($id);

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', '¡Lo sentimos! Este producto está agotado.');
        }

        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        
        if (($currentQty + 1) > $product->stock) {
            return redirect()->back()->with('error', 'No puedes añadir más unidades. ¡No queda suficiente stock!');
        }

        // --- LÓGICA DE DESCUENTO ---
        $finalPrice = $product->price;
        $dailyDeal = Product::getDailyDeal();
        $isDeal = ($dailyDeal && $dailyDeal->id == $product->id);

        if ($isDeal) {
            $finalPrice = $product->price / 2; // 50% OFF
        }

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name . ($isDeal ? ' (🔥 OFERTA)' : ''),
                "quantity" => 1,
                "price" => $finalPrice,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        $msg = $isDeal 
            ? '¡Ofertón añadido! Has conseguido un 50% de descuento 🔥' 
            : 'Producto añadido correctamente ✅';

        return redirect()->back()->with('success', $msg);
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
            'street' => 'required|string|min:5',
            'city' => 'required|string|min:2',
            'zip' => 'required|string|min:4',
            'country' => 'required|string',
            'card_number' => 'required|numeric',
        ]);

        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['quantity'];

        DB::beginTransaction();

        try {
            foreach($cart as $id => $item) {
                $product = Product::lockForUpdate()->find($id);
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', "El producto {$product->name} ya no tiene suficiente stock.");
                }
            }

            $fullAddress = $request->street . ', ' . $request->city . ' (' . $request->zip . ') - ' . $request->country;

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'status' => 'pagado',
                'shipping_address' => $fullAddress,
                'payment_method' => 'tarjeta'
            ]);

            foreach($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                $product = Product::find($item['id']);
                $product->stock = $product->stock - $item['quantity'];
                $product->save();
            }

            DB::commit();
            session()->forget('cart');
            return view('cart.success', compact('order'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }
}