<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    protected $table = 'tbdenunciacomentario';
    protected $primaryKey = 'idDenuncia';
    
    protected $fillable = [
        'idComentario',
        'idUser',
        'motivoDenuncia',
        'statusDenuncia',
        'resultado'
    ];

    public function comentario()
    {
        return $this->belongsTo(Comentario::class, 'idComentario');
       
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }
}   