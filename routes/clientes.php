<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Cliente;

Route::get('/clientes', function(Request $request) {
    $clientes = Cliente::listRecords();
    return view('clientes', compact('clientes'));
})->name('clientes.index');


Route::post('/clientes', function(Request $request) {

    $data = $request->validate([
        'identificacion' => 'required|string|max:50|unique:clientes,identificacion',
        'nombre'         => 'required|string|max:100',
        'apellido'       => 'required|string|max:100',
        'telefono'       => 'nullable|string|max:20',
        'direccion'      => 'nullable|string|max:255',
    ]);


   Cliente::createRecord([
        'identificacion' => $data['identificacion'],
        'nombre'         => $data['nombre'],
        'apellido'       => $data['apellido'],
        'telefono'       => $data['telefono'],
        'direccion'      => $data['direccion'],
        'saldo'          => 0,
    ]);

    return redirect()
        ->route('clientes.index')
        ->with('status', 'Cliente agregado correctamente.');
})->name('clientes.store');
