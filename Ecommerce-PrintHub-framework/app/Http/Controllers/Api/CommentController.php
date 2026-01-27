<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // GET: Retorna els comentaris d'un producte (Públic)
    public function index($productId)
    {
        $comments = Comment::where('product_id', $productId)
                           ->with('user:id,name,surname') // Portem només les dades necessàries de l'usuari
                           ->orderBy('created_at', 'desc')
                           ->get();
                           
        return response()->json($comments);
    }

    // POST: Guarda un nou comentari (Només usuaris loguejats)
    public function store(Request $request, $productId)
    {
        // Validació al servidor (seguretat)
        $validated = $request->validate([
            'text' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'product_id' => $productId,
            'text' => $validated['text'],
            'rating' => $validated['rating'],
        ]);

        // Retornem el comentari amb les dades de l'usuari per mostrar-lo a l'instant al JS
        $comment->load('user:id,name,surname');

        return response()->json($comment, 201);
    }
}