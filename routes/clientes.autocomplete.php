<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


// Route::get('/clientes/autocomplete', function(Request $request) {
//     $term = $request->input('term', '');
//     $nombres = DB::table('clientes')
//         ->where('nombre', 'like', "%{$term}%")
//         ->orderBy('nombre')
//         ->limit(10)
//         ->pluck('nombre');
//     return response()->json($nombres);
// })->name('clientes.autocomplete');


Route::get('/clientes/autocomplete', function(Request $request) {
    $term = $request->input('term', '');
    $query = DB::table('clientes')
        ->select('*')
        ->orderBy('nombre')
        ->limit(4);
    if ($term !== '') {
        $query->where('nombre', 'like', "%{$term}%");
    }
    $nombres = $query->get();
    return response()->json($nombres);
})->name('clientes.autocomplete');