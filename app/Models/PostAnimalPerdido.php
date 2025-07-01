<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAnimalPerdido extends Model
{
    protected $table = 'tbpostanimalperdido';
    protected $primaryKey = 'idPostAnimalPerdido';
    
    protected $fillable = [
        'idAnimal',
        'idUser'
    ];
    
    public $timestamps = false;
    
    /**
     * Relação com o animal
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'idAnimal', 'idAnimal');
    }
    
    /**
     * Relação com o usuário
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }
}