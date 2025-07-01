<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Raca;
use App\Models\Especie;
use App\Models\Pelagem; 
use App\Models\Porte;
use App\Models\StatusAnimal;
use App\Models\ImagensAnimal;
use App\Models\HistoricoAnimal;
use App\Models\Ong;

class Animal extends Model
{
    protected $table = 'tbanimal';
    protected $primaryKey = 'idAnimal';
    
    protected $fillable = [
        'nomeAnimal', 'idadeAnimal', 'idRaca', 'idPorte', 'idEspecie',
        'idPelagemAnimal', 'idStatusAnimal', 'bioAnimal', 'imgPrincipal', 'idOng'
    ];
    
    public $timestamps = false;
    
    public function raca()
    {
        return $this->belongsTo(Raca::class, 'idRaca', 'idRaca');
    }
    
    public function especie()
    {
        return $this->belongsTo(Especie::class, 'idEspecie', 'idEspecie'); 
    }
    
    public function pelagem()
    {
        return $this->belongsTo(Pelagem::class, 'idPelagemAnimal', 'idPelagemAnimal');
    }
    
    public function porte()
    {
        return $this->belongsTo(Porte::class, 'idPorte', 'idPorte');
    }
    
    public function status()
    {
        return $this->belongsTo(StatusAnimal::class, 'idStatusAnimal', 'idStatusAnimal');
    }
    
    // Adicione este relacionamento crucial
    public function ong()
    {
        return $this->belongsTo(Ong::class, 'idOng', 'idOng');
    }
    
    public function imagensAnimal()
    {
        return $this->hasOne(ImagensAnimal::class, 'idAnimal', 'idAnimal');
    }
    
    public function historicoAnimal()
    {
        return $this->hasMany(HistoricoAnimal::class, 'idAnimal', 'idAnimal');
    }
    
    public function interesses()
    {
        return $this->hasMany(Interesse::class, 'idAnimal');
    }

}