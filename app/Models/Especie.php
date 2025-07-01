<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    use HasFactory;

    protected $table = 'tbespecie';

    protected $primaryKey = 'idEspecie';

    public $timestamps = false;

    protected $fillable = [
        'nomeEspecie',
    ];

    // Definir a relação de "uma espécie tem muitas raças"
    public function racas()
    {
        return $this->hasMany(Raca::class, 'idEspecie', 'idEspecie');
    }
}

