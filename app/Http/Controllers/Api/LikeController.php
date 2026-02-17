<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Support\Facades\Auth; // <--- Importante

class LikeController extends Controller
{
    // Dar o quitar like
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

    // Comprobar si el usuario dio like
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