<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbpreferenciasUsuario extends Model
{
    protected $table = 'tbpreferencias_usuario';
    protected $primaryKey = 'idPreferencia';
    public $timestamps = false;

    protected $fillable = [
        'idUser',
        'especie_preferida',
        'porte_preferido'
    ];

    protected $casts = [
        'porte_preferido' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'especie_preferida', 'idEspecie');
    }
}
