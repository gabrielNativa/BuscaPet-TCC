<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusAnimal extends Model
{
    use HasFactory;

    protected $table = 'tbstatusanimal';
    protected $primaryKey = 'idStatusAnimal';
    public $timestamps = false;

    // Verifique o nome exato da coluna no banco e atualize aqui
    protected $fillable = ['descStatusAnimal']; 

    // Adicione este acesso para uniformizar
    public function getNomeAttribute()
    {
        return $this->statusAnimal ?? $this->nomeStatusAnimal;
    }
}

