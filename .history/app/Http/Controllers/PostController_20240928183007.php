<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Mostrar el formulario de creación de un nuevo post
    public function create()
    {
        return view('posts.create');  // Retorna la vista para crear un post
    }

    // Lógica para almacenar el post
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Crear el post
        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Manejo de imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Almacenar la imagen y optimizarla
                $imagePath = $image->store('posts', 'public');
                $img = InterventionImage::make(storage_path("app/public/{$imagePath}"))->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save();

                // Guardar la imagen en la base de datos
                Image::create([
                    'post_id' => $post->id,
                    'path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('feed');  // Redirigir al feed después de publicar
    }
}
