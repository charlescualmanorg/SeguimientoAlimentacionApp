<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'content'];

    // Relación de un post con varias imágenes
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // Relación de un post con el usuario que lo creó
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación de un post con los comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relación de un post con los likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(User $user)
    {
            // Si no hay usuario autenticado, devolvemos false
        if (!$user) {
            return false;
        }
        // Verificar que el post tenga likes antes de intentar obtener la información
        return $this->likes->isNotEmpty() && $this->likes->contains('user_id', $user->id);
    }

}
