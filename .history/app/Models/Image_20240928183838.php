<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['post_id', 'path'];

    // Relación de la imagen con su post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
