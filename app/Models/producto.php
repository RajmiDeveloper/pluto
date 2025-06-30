<?php

namespace App\Models;

use App\Models\BaseModel;

class Producto extends BaseModel
{
    protected $table = 'productos';
    protected $fillable = [
        'descripcion',
        'stock',
        'precio',
        'telefono',
    ];
}