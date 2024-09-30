<?php

namespace App\Http\Controllers;

use App\Models\Post;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'comments.user', 'likes')->latest()->paginate(10);
        return view('feed', compact('posts'));
    }
}
