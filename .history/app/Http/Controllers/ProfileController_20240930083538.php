<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('perfil');
    }
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'age' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'diseases' => 'nullable|array',
            'diseases.*' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Manejar la imagen de perfil
        if ($request->hasFile('profile_image')) {
            // Eliminar imagen anterior si existe
            if ($user->profile_image) {
                Storage::delete('public/' . $user->profile_image);
            }

            // Subir nueva imagen
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        // Actualizar los demás datos
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->username = $request->username;
        $user->age = $request->age;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->diseases =  json_encode($request->diseases);
        
        $user->save();

        return back()->with('success', 'Perfil actualizado con éxito.');
    }
}
