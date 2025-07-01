<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ong extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbong';
    protected $primaryKey = 'idOng';
    public $timestamps = false; // Desativa os timestamps automáticos

    protected $fillable = [
        'nomeOng',
        'cnpjOng',
        'emailOng',
        'senhaOng',
        'telOng',
        'lograOng',
        'cepOng',
        'status',
        'fotoOng', 
         'motivo_rejeicao'
    ];

    protected $hidden = [
        'senhaOng',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->senhaOng;
    }
    public function animais()
{
    return $this->hasMany(Animal::class, 'idOng');
}

public function posts()
{
    return $this->hasMany(Post::class, 'idOng');
}
}