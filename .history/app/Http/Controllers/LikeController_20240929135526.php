<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // Método para dar o quitar like
    public function toggleLike($postId)
    {
        $post = Post::findOrFail($postId);
        $user = Auth::user();

        // Si ya tiene un like en este post, eliminarlo
        if ($post->isLikedBy($user)) {
            $post->likes()->where('user_id', $user->id)->delete();
            return redirect()->back()->with('success', 'Like eliminado.');
        }

        // Si no tiene like, añadirlo
        Like::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Like agregado.');
    }
}
