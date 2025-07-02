<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/facturas', function() {
    $facturas = DB::table('movimientos')
        // Ajusta el campo de unión según tu esquema:
        ->join('clientes', 'movimientos.cliente_id', '=', 'clientes.id')
        // Selecciono todos los campos de movimientos + el nombre del cliente
        ->select(
            'movimientos.*',
            'clientes.nombre as cliente_nombre'
        )
        ->get();

    return view('facturas', compact('facturas'));
})->name('facturadas.index');
