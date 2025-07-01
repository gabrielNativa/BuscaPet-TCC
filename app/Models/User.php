<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $table = 'tbuser';

    
    protected $primaryKey = 'idUser';

    
    public $timestamps = false;

    
    protected $fillable = [
        'nomeUser',
        'cpfUser',
        'emailUser',
        'senhaUser',
        'telUser',
        'lograUser',
        'numLograUser',
        'cepUser',
        'ufUser',
        'cidadeUser',
        'compUser',
        'paisUser',
        'dataNascUser',
        'imgUser',
        'ativo'
    ];


    public function likes()
{
    return $this->hasMany('App\Models\Like', 'idUser');
}

public function comments()
{
    return $this->hasMany('App\Models\Comentario', 'idUser');
}
    
}

