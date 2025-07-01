<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaPost extends Model
{
    protected $table = 'tbcategoriaposts';
    protected $primaryKey = 'idCategoriaPosts';
    public $timestamps = false;
    
}