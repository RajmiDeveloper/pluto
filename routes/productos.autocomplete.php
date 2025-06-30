<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Producto;

// Endpoint para autocompletar productos
Route::get('/productos/autocomplete', function(Request $request) {
    $term = $request->input('term', '');
    $productos = Producto::query()
        ->where('descripcion', 'like', "%{$term}%")
        ->orderBy('descripcion')
        ->limit(10)
        ->get(['id','descripcion','precio']);

    return response()->json($productos);
})->name('productos.autocomplete');
