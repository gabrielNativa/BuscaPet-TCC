<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    use HasFactory;

    protected $table = 'tbraca';

    protected $primaryKey = 'idRaca';

    public $timestamps = false;

    protected $fillable = [
        'nomeRaca',
        'idEspecie',
    ];

    // Definir a relação de "uma raça pertence a uma espécie"
    public function especie()
    {
        return $this->belongsTo(Especie::class, 'idEspecie', 'idEspecie');
    }
}

