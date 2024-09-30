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

        $post = Post::findOrFail($postId);

        // Crear el comentario
        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Comentario publicado.');
    }
}
