<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar imágenes
        ]);

        // Crear el post
        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Manejar las imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Optimizar la imagen
                $imagePath = $image->store('posts', 'public');
                $img = InterventionImage::make(storage_path("app/public/{$imagePath}"))->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save();

                // Guardar la ruta de la imagen en la base de datos
                Image::create([
                    'post_id' => $post->id,
                    'path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('feed');
    }
}
