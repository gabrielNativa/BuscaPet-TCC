<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelagem extends Model
{
    use HasFactory;

    protected $table = 'tbPelagemAnimal';

    
    protected $primaryKey = 'idPelagemAnimal';

    
    public $timestamps = false;

    
    protected $fillable = [
        'PelagemAnimal',
    ];

}
