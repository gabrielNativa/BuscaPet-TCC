<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoAnimal extends Model
{
    protected $table = 'tbhistoricoanimal';
    protected $primaryKey = 'idHistoricoAnimal';
    
    protected $fillable = [
        'dataHistoricoAnimal',
        'horaHistoricoAnimal',
        'detalhesHistoricoAnimal',
        'idAnimal'
    ];
    
    public $timestamps = false;
    
    /**
     * Relacionamento com o animal
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'idAnimal', 'idAnimal');
    }
}