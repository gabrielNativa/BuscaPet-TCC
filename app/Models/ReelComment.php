<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReelComment extends Model
{
    protected $table = 'tbreel_comentarios';
    protected $primaryKey = 'idComentario';

    protected $fillable = [
        'idReels',
        'idUser',
        'comentario'
    ];

    public function reel()
    {
        return $this->belongsTo(Reel::class, 'idReels');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
} 