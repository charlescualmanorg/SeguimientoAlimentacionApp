<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Post $post)
    {
        if (!$post->likes->where('user_id', Auth::id())->count()) {
            Like::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('feed');
    }
}
