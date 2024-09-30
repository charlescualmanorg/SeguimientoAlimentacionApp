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
    // Mostrar formulario de creación de post
    public function create()
    {
        return view('posts.create');
    }

    // Guardar post y subir imágenes
    public function store(Request $request)
    {
        // Validar datos del formulario
        $request->validate([
            'content' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validar las imágenes
        ]);

        // Crear el post
        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Manejar las imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Almacenar la imagen
                $imagePath = $image->store('posts', 'public');

                // Optimizar la imagen
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

        return redirect()->route('feed');
    }

    public function destroy($id)
    {
        // Encontrar el post por su ID
        $post = Post::findOrFail($id);

        // Verificar si el usuario autenticado es el creador del post
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('feed')->with('error', 'No tienes permiso para eliminar este post.');
        }

        // Eliminar las imágenes asociadas con el post
        foreach ($post->images as $image) {
            // Eliminar la imagen del almacenamiento
            \Storage::delete('public/' . $image->path);
        }

        // Eliminar las imágenes relacionadas de la base de datos
        $post->images()->delete();

        // Finalmente, eliminar el post
        $post->delete();

        return redirect()->route('feed')->with('success', 'Post eliminado exitosamente.');
    }

    public function like(Post $post)
    {
        $user = auth()->user();
    
        // Verificar si el usuario ya ha dado like al post
        $like = $post->likes()->where('user_id', $user->id)->first();
    
        if ($like) {
            // Si ya dio like, eliminarlo
            $like->delete();
            return back()->with('success', 'Has quitado tu like.');
        } else {
            // Si no ha dado like, agregarlo
            $post->likes()->create([
                'user_id' => $user->id,
            ]);
            return back()->with('success', 'Has dado like al post.');
        }
    }
    

}

