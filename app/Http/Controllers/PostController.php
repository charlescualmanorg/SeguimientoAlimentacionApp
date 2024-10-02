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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',  // Validar las imágenes
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
                //se agrego orientate para que prevalezca la orientacion de fotografias iphone
                $img = InterventionImage::make(storage_path("app/public/{$imagePath}"))->orientate();
                
                if ($image->getSize() > 2000000) {
                    // Determinar la orientación de la imagen
                    $width = $img->width();
                    $height = $img->height();
        
                    if ($width > $height) {
                        // La imagen es Landscape (horizontal), ajustar el ancho a 1200px
                        $img->resize(1200, null, function ($constraint) {
                            $constraint->aspectRatio();  // Mantener la proporción
                            $constraint->upsize();       // Evitar que se agrande si ya es menor
                        });
                    } else {
                        // La imagen es Portrait (vertical), ajustar el alto a 1200px
                        $img->resize(null, 1200, function ($constraint) {
                            $constraint->aspectRatio();  // Mantener la proporción
                            $constraint->upsize();       // Evitar que se agrande si ya es menor
                        });
                    }
        
                    // Comprimir la imagen al 75% de calidad
                    $img->encode('jpg', 75);
                }


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
    
    public function index()
    {
        $userId = Auth::id();
        // Obtener las fechas de publicaciones
        $dates = Post::selectRaw('DATE(created_at) as date')
            ->where('user_id', $userId)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('index', compact('dates'));
    }

    // Método para obtener las publicaciones de una fecha específica
    public function getPublicationsForDate($date)
    {
        //dd($date);
        try {
            // Validar el formato de la fecha
            if (!\Carbon\Carbon::hasFormat($date, 'Y-m-d')) {
                return response()->json(['error' => 'Formato de fecha inválido. Debe ser Y-m-d.'], 400);
            }
    
            $userId = Auth::id();
    
            // Obtener las publicaciones del usuario con las imágenes asociadas
            $publications = Post::with('images')
                ->where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->get();
    
            // Verificar si hay publicaciones
            if ($publications->isEmpty()) {
                return response()->json(['message' => 'No se encontraron publicaciones para esta fecha.'], 404);
            }
    
            // Formatear la respuesta
            $response = $publications->map(function($publication) {
                return [
                    'id' => $publication->id,
                    'content' => $publication->content,
                    'created_at' => $publication->created_at->format('Y-m-d H:i:s'),
                    'images' => $publication->images->map(function($image) {
                        return [
                            'path' => $image->path,
                        ];
                    }),
                ];
            });
    
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener publicaciones: ' . $e->getMessage()], 500);
        }
    }

}

