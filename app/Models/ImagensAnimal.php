<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagensAnimal extends Model
{
    protected $table = 'tbimagensanimal';
    protected $primaryKey = 'idImagemAnimal';
    
    protected $fillable = [
        'img1Animal', 'img2Animal', 'img3Animal', 'img4Animal', 'idAnimal'
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