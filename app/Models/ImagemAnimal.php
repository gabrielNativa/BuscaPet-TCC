<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemAnimal extends Model
{
    use HasFactory;

    protected $table = 'tbimagensanimal';
    protected $primaryKey = 'idImagemAnimal';
    public $timestamps = false;

    protected $fillable = [
        'imgPrincipal', 'img1Animal', 'img2Animal', 'img3Animal', 'img4Animal', 'idAnimal'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'idAnimal');
    }
}