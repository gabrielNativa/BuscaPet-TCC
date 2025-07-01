<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAdocao extends Model
{
    use HasFactory;

    // Nome da tabela no banco de dados
    protected $table = 'tbadocao';
    
    // Chave primária da tabela
    protected $primaryKey = 'idAdocao';
    
    // Indica se o modelo deve registrar timestamps
    public $timestamps = false;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'idPostAdocao',
        'dataAdocao',
        'idUser'
    ];

    // Conversões de tipos
    protected $casts = [
        'dataAdocao' => 'date:d/m/Y', // Formato brasileiro
    ];

    /**
     * Relacionamento com o PostAdocao (tabela tbpostadocao)
     */
    public function postAdocao()
    {
        return $this->belongsTo(PostAdocao::class, 'idPostAdocao', 'idPostAdocao');
    }

    /**
     * Relacionamento com o Usuário que adotou
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    /**
     * Relacionamento com o Animal (através de PostAdocao)
     */
    public function animal()
    {
        return $this->hasOneThrough(
            Animal::class,
            PostAdocao::class,
            'idPostAdocao', // FK na tabela intermediária (tbpostadocao)
            'idAnimal',      // FK na tabela final (tbanimal)
            'idPostAdocao',  // Local key na tabela atual (tbadocao)
            'idAnimal'       // Local key na tabela intermediária (tbpostadocao)
        );
    }
}