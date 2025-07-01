<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'tbcomentario';

    protected $primaryKey = 'idComentario';

    protected $fillable = [
        'idUser',    
        'id',        
        'comment',   
        'created_at',
        'visivel',   
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'id', 'id');
    }
    
    public function scopeVisible($query)
    {
        return $query->where('visivel', 1);
    }
}
