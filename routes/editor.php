<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('editor/{table}/{id}/{back?}', function(Request $request, $table, $id, $back = '') {
    // Lista blanca de tablas que puedes editar
    $allowed = ['clientes','productos','movimientos','detalles_factura_venta'];
    if (! in_array($table, $allowed)) {
        abort(404);
    }

    $record = DB::table($table)->where('id', $id)->first();
    if (! $record) {
        abort(404);
    }

    $columns = array_filter(
        array_keys((array) $record),
        fn($col) => ! in_array($col, ['id','created_at','updated_at'])
    );

    return view('editor', compact('table','id','record','columns','back'));
})->name('editor.edit');

Route::post('editor/{table}/{id}/{back?}', function(Request $request, $table, $id, $back = '') {
    $allowed = ['clientes','productos','movimientos','detalles_factura_venta'];
    if (! in_array($table, $allowed)) {
        abort(404);
    }
    $record = DB::table($table)->where('id', $id)->first();
    if (! $record) {
        abort(404);
    }
    $columns = array_filter(
        array_keys((array) $record),
        fn($col) => ! in_array($col, ['id','created_at','updated_at'])
    );

    $rules = [];
    foreach ($columns as $col) {
        $rules[$col] = 'nullable|string|max:255';
    }
    $data = $request->validate($rules);

    DB::table($table)
      ->where('id', $id)
      ->update($data);

    return redirect()
        ->route('editor.edit', ['table' => $table, 'id' => $id, 'back' => $back])
        ->with('status', 'Registro actualizado.');
})->name('editor.update');
