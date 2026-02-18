<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|array',
            'shipping_address.street' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.zip' => 'required|string',
            'shipping_address.country' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // 1. VERIFICACIÓN DE STOCK
            foreach ($validated['items'] as $item) {
                // Bloqueamos el registro para evitar conflictos de stock concurrentes
                $product = Product::lockForUpdate()->find($item['id']); 
                
                if (!$product) {
                    throw new \Exception("El producto con ID {$item['id']} no existe.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para: " . $product->name);
                }
            }

            // 2. CREAR EL PEDIDO
            $addressString = "{$validated['shipping_address']['street']}, {$validated['shipping_address']['city']}, {$validated['shipping_address']['zip']}, {$validated['shipping_address']['country']}";

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $validated['total'],
                'shipping_address' => $addressString,
                'status' => 'pending',
                'payment_method' => 'card',
            ]);

            // 3. GUARDAR ITEMS Y REDUCIR STOCK
            foreach ($validated['items'] as $item) {
                // Recuperamos el producto para obtener su nombre real y restar stock
                $product = Product::find($item['id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $product->name, // <--- AQUÍ SOLUCIONAMOS EL ERROR
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Restamos el stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pedido realizado correctamente',
                'order' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}