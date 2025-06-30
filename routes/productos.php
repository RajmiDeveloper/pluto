<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Producto;

Route::get('/productos', function(Request $request) {
    $productos = Producto::listRecords();
    return view('productos', compact('productos'));
})->name('productos.index');


Route::post('/productos', function(Request $request) {

    $data = $request->validate([
        'descripcion'   => 'required|string|max:100',
        'stock'         => 'required|integer',
        'precio'      => 'required|decimal:0,2|min:0',
    ]);


   Producto::createRecord([
        'descripcion'   => $data['descripcion'],
        'stock'         => $data['stock'],
        'precio'        => $data['precio'],
    ]);

    return redirect()
        ->route('productos.index')
        ->with('status', 'Producto agregado correctamente.');
})->name('productos.store');
