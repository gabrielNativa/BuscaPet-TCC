<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Porte extends Model
{
    use HasFactory;

    protected $table = 'tbporte';

    
    protected $primaryKey = 'idPorte';

    
    public $timestamps = false;

    
    protected $fillable = [
        'nomePorte',
    ];

}
