<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Support\Facades\Auth; // <--- Importante

/**
 * @group Favoritos (Likes)
 *
 * APIs para interactuar con el sistema de 'Me gusta' de los productos.
 */
class LikeController extends Controller
{
    /**
     * Dar o quitar Like
     *
     * Alterna el estado de favorito del usuario sobre un producto. Si ya tenía like, se lo quita, si no, se lo añade.
     *
     * @authenticated
     * @urlParam productId int required El ID del producto al que se le da/quita like. Example: 1
     */
    public function toggle($productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $existingLike = Like::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $status = 'unliked';
        } else {
            Like::create([
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

    /**
     * Comprobar Like actual
     *
     * Verifica si el usuario autenticado ya ha marcado este producto como favorito. Útil para cargar el botón inicial en Vue.
     *
     * @authenticated
     * @urlParam productId int required El ID del producto a comprobar. Example: 1
     */
    public function check($productId)
    {
        $user = Auth::user();
        $isLiked = Like::where('user_id', $user->id)
                       ->where('product_id', $productId)
                       ->exists();

        return response()->json([
            'is_liked' => $isLiked,
            'likes_count' => Product::findOrFail($productId)->likes()->count()
        ]);
    }
}