<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

/**
 * @group Comentarios y Valoraciones
 *
 * APIs para gestionar las reseñas que los usuarios dejan en los productos.
 */
class CommentController extends Controller
{
    /**
     * Listar comentarios de un producto
     *
     * Obtiene la lista completa de reseñas asociadas a un producto en específico. Es una ruta pública.
     *
     * @urlParam productId int required El ID numérico del producto. Example: 1
     */
    public function index($productId)
    {
        $comments = Comment::where('product_id', $productId)
                           ->with('user:id,name,surname,role') // Traemos el rol para el JS
                           ->latest()
                           ->get();
        return response()->json($comments);
    }

    /**
     * Añadir comentario
     *
     * Escribe una reseña y otorga una puntuación (1 a 5) a un producto.
     *
     * @authenticated
     * @urlParam productId int required El ID del producto que se va a comentar. Example: 1
     * @bodyParam text string required El contenido de la reseña (máx 500 caract.). Example: ¡La mejor impresora 3D que he comprado!
     * @bodyParam rating int required La puntuación del 1 al 5. Example: 5
     */
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

    /**
     * Editar comentario
     *
     * Modifica el texto o la puntuación de un comentario existente. Solo el autor original puede editarlo.
     *
     * @authenticated
     * @urlParam comment int required El ID del comentario a editar. Example: 5
     * @bodyParam text string required El nuevo texto. Example: Actualizo reseña: sigue funcionando genial.
     * @bodyParam rating int required La nueva puntuación del 1 al 5. Example: 4
     */
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

    /**
     * Eliminar comentario
     *
     * Borra un comentario. Solo puede hacerlo el autor del comentario o un Administrador.
     *
     * @authenticated
     * @urlParam comment int required El ID del comentario a borrar. Example: 5
     */
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