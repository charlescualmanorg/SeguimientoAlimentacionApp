<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $post = Post::findOrFail($post->id);

        // Crear el comentario
        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'text' => $request->content
        ]);

        return redirect()->back()->with('success', 'Comentario publicado.');
    }

    // Método para eliminar un comentario
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Solo el autor del comentario o el propietario del post pueden eliminarlo
        if (Auth::id() !== $comment->user_id && Auth::id() !== $comment->post->user_id) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este comentario.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comentario eliminado.');
    }
}
