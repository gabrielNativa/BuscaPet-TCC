<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    use HasFactory;

    protected $table = 'reels';
    protected $primaryKey = 'idReels';

    protected $fillable = [
        'idUser',
        'tituloReels',
        'descricaoReels',
        'video_urlReels',
        'thumbnail_urlReels',
        'duracaoReels',
        'visualizacoesReels',
        'likesReels',
        'comentarios_countReels',
        'pet_nomeReels',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'visualizacoesReels' => 'integer',
        'likesReels' => 'integer',
        'comentarios_countReels' => 'integer',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    // Relacionamento com Likes
    public function likes()
    {
        return $this->hasMany(ReelLike::class, 'idReels');
    }

    // Relacionamento com Comentários
    public function comments()
    {
        return $this->hasMany(ReelComment::class, 'idReels');
    }

    // Verificar se usuário curtiu o reel
    public function isLikedByUser($idUser)
    {
        return $this->likes()->where('idUser', $idUser)->exists();
    }

    // Incrementar visualizações
    public function incrementViews()
    {
        $this->increment('visualizacoesReels');
    }

    // Atualizar contadores
    public function updateCounters()
    {
        $this->update([
            'likesReels' => $this->likes()->count(),
            'comentarios_countReels' => $this->comments()->count()
        ]);
    }
}