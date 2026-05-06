<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * @group Pedidos y Checkout
 *
 * APIs para procesar el carrito de compras y generar pedidos.
 */
class CheckoutController extends Controller
{
    /**
     * Finalizar compra (Checkout)
     *
     * Procesa los productos del carrito, verifica si hay stock suficiente, crea el pedido y resta el stock de la base de datos.
     *
     * @authenticated
     * @bodyParam shipping_address object required Dirección de envío.
     * @bodyParam shipping_address.street string required Calle y número. Example: Calle Mayor 1
     * @bodyParam shipping_address.city string required Ciudad. Example: Alcoy
     * @bodyParam shipping_address.zip string required Código Postal. Example: 03801
     * @bodyParam shipping_address.country string required País. Example: España
     * @bodyParam items object[] required Array con los productos a comprar.
     * @bodyParam items[].id int required ID del producto. Example: 1
     * @bodyParam items[].quantity int required Cantidad del producto. Example: 2
     * @bodyParam items[].price float required Precio unitario. Example: 19.99
     * @bodyParam total float required Precio total del pedido. Example: 39.98
     */
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