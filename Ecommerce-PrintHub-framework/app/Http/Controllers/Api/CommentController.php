<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Devuelve los comentarios de un producto
    public function index($productId)
    {
        $comments = Comment::where('product_id', $productId)
                           ->with('user:id,name,surname') // Solo nombre del usuario
                           ->latest()
                           ->get();
                           
        return response()->json($comments);
    }

    // Guarda un comentario nuevo
    public function store(Request $request, $productId)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'product_id' => $productId,
            'text' => $request->text,
            'rating' => $request->rating,
        ]);

        return response()->json($comment, 201);
    }
}