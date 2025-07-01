<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoriaPost;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'views',
        'likes',
        'saves',
        'idOng',
        'idCategoriaPosts'
    ];

    public function ong()
    {
        return $this->belongsTo(Ong::class, 'idOng', 'idOng');
    }
    public function likes()
{
    return $this->hasMany('App\Models\Like', 'id');
}

public function comments()
{
    return $this->hasMany('App\Models\Comentario', 'id');
}

public function categoria()
{
    return $this->belongsTo(CategoriaPost::class, 'idCategoriaPosts');
}


}
