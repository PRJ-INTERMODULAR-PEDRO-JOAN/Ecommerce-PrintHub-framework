<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // Dar o Quitar Like (Toggle)
    public function toggle(Request $request, $productId)
    {
        $user = $request->user();
        $product = Product::findOrFail($productId);

        // Buscamos si ya existe el like
        $existingLike = Like::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete(); // Si existe, lo borramos (Dislike)
            $status = 'unliked';
        } else {
            Like::create([ // Si no existe, lo creamos (Like)
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $product->likes()->count()
        ]);
    }

    // Obtener estado del like (para pintar el botón al cargar)
    public function check(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = $request->user('sanctum'); // Intentamos obtener usuario (puede ser null)

        return response()->json([
            'is_liked' => $user ? $product->isLikedBy($user) : false,
            'likes_count' => $product->likes()->count()
        ]);
    }
}