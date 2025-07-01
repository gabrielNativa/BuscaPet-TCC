<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelComentario extends Model
{
    use HasFactory;

    protected $table = 'tbreel_comentarios';

    protected $fillable = [
        'idReels',
        'idUser',
        'comentario'
    ];

    public $timestamps = true;

    // Relacionamento com Reel
    public function reel()
    {
        return $this->belongsTo(Reel::class, 'idReels');
    }

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    // Boot method para atualizar contador quando comentário é criado/deletado
    protected static function boot()
    {
        parent::boot();

        static::created(function ($comentario) {
            $comentario->reel->increment('comentarios_countReels');
        });

        static::deleted(function ($comentario) {
            $comentario->reel->decrement('comentarios_countReels');
        });
    }
}