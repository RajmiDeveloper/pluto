<?php

namespace App\Models;

use App\Models\BaseModel;

class Cliente extends BaseModel
{
    protected $table = 'clientes';
    protected $fillable = [
        'identificacion',
        'nombre',
        'apellido',
        'telefono',
        'direccion',
        'saldo',
    ];
}