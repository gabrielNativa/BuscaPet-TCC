<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelLike extends Model
{
    use HasFactory;

    protected $table = 'tbreel_likes';
    protected $primaryKey = 'idLike';

    protected $fillable = [
        'idReels',
        'idUser'
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

    // Boot method para atualizar contador quando like é criado/deletado
    protected static function boot()
    {
        parent::boot();

        static::created(function ($like) {
            $like->reel->increment('likesReels');
        });

        static::deleted(function ($like) {
            $like->reel->decrement('likesReels');
        });
    }
}