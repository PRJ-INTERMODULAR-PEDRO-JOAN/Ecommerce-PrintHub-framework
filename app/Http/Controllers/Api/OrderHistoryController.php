<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderHistoryController extends Controller
{
    /**
     * Obtiene el historial de pedidos del usuario autenticado.
     */
    public function index(Request $request)
    {
        // Busca los pedidos del usuario actual, incluyendo los items y la info del producto
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product') 
            ->orderBy('created_at', 'desc') // Los más recientes primero
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}