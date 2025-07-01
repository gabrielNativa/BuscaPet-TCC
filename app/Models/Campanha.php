<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campanha extends Model
{
    use HasFactory;

    protected $table = 'tbcampanha';


    protected $primaryKey = 'idCampanha';

    protected $fillable = [
        'idOng',
        'tituloCampanha',
        'descricaoCampanha',
        'dataInicio',
        'dataFim',
        'ativo',
        'imagemCampanha',
    ];
    public function ong()
    {
        return $this->belongsTo(Ong::class, 'idOng', 'idOng');
    }
}
