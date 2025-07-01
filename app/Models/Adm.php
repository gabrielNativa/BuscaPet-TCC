<?php

namespace App\Models;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adm extends Model implements Authenticatable
{
    use HasFactory, \Illuminate\Auth\Authenticatable;

    
    protected $table = 'tbadm';

    
    protected $primaryKey = 'idAdm';

    
    public $timestamps = false;

    
    protected $fillable = [
        'nomeAdm',
        'cpfAdm',
        'emailAdm',
        'senhaAdm',
        'telAdm',
        'lograAdm',
        'numLograAdm',
        'cepAdm',
        'ufAdm',
        'cidadeAdm',
        'bairroAdm',
        'compAdm',
        'paisAdm',
        'dataNascAdm',
        'imgAdm',
    ];
}
