<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // Especifica a tabela do banco de dados
    protected $table = 'tblike';

    // Atributos que podem ser atribuídos em massa
    protected $fillable = [
        'idUser',    // ID do usuário que curtiu o post
        'id',    // ID do post que foi curtido
        'created_at', // Data de criação do like
    ];

    // Relação com o modelo User (usuário que fez o like)
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    // Relação com o modelo Post (post que foi curtido)
    public function post()
    {
        return $this->belongsTo(Post::class, 'id', 'id');
    }
}
