<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // Mostrar el perfil del usuario autenticado
    public function show()
    {
        $user = Auth::user();

        // Retornar la vista del perfil con el usuario autenticado
        return view('profile', compact('user'));
    }

    // Actualizar el perfil del usuario autenticado
    public function update(Request $request)
    {
        dd($request);
        $user = Auth::user();

        // Validar los campos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'usuario' => 'required|string|max:255|unique:users,usuario,' . $user->id,
            'edad' => 'required|integer|min:0',
            'peso' => 'required|numeric|min:0',
            'altura' => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'diseases' => 'nullable|array',
            'diseases.*' => 'nullable|string',
        ]);

        // Actualizar los datos del usuario
        $user->name = $validated['name'];
        $user->apellidos = $validated['apellidos'];
        $user->usuario = $validated['usuario'];
        $user->edad = $validated['edad'];
        $user->peso = $validated['peso'];
        $user->altura = $validated['altura'];

        // Manejo de la imagen de perfil
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        // Guardar enfermedades preexistentes como JSON
        if ($request->has('diseases')) {
            $user->diseases = json_encode(array_filter($request->input('diseases'))); // Filtrar inputs vacíos
        }

        $user->save();

        return redirect()->route('profile.show')->with('status', 'Perfil actualizado exitosamente.');
    }
}
