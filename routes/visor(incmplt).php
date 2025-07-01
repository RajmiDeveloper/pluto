<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('visor/{table}/{id}/{back?}', function(Request $request, $table,$field ,$id,$name, $back = '') {
    // Lista blanca de tablas que puedes editar
    $allowed = ['clientes','productos','movimientos','detalles_factura_venta'];
    if (! in_array($table, $allowed)) {
        abort(404);
    }

    $record = DB::table($table)->where($field, $id)->first();
    if (! $record) {
        abort(404);
    }

    $columns = array_filter(
        array_keys((array) $record),
        fn($col) => ! in_array($col, ['id','created_at','updated_at'])
    );

    return view('visor', compact('table','id','record','columns','back','name'));
})->name('visor.index');
