<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // GET: Ver comentarios
    public function index($productId)
    {
        $comments = Comment::where('product_id', $productId)
                           ->with('user:id,name,surname,role') // Traemos el rol para el JS
                           ->latest()
                           ->get();
        return response()->json($comments);
    }

    // POST: Crear comentario
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

        $comment->load('user:id,name,surname,role');
        return response()->json($comment, 201);
    }

    // PUT: Editar comentario (Solo el dueño)
    public function update(Request $request, Comment $comment)
    {
        if ($request->user()->id !== $comment->user_id) {
            return response()->json(['message' => 'No tienes permiso para editar esto.'], 403);
        }

        $request->validate([
            'text' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment->update([
            'text' => $request->text,
            'rating' => $request->rating
        ]);

        return response()->json($comment);
    }

    // DELETE: Borrar comentario (Dueño o Admin)
    public function destroy(Request $request, Comment $comment)
    {
        $user = $request->user();

        // Permitir si es el dueño O si es admin
        if ($user->id !== $comment->user_id && $user->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para borrar esto.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado']);
    }
}