<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Editor genérico: formulario de edición
Route::get('editor/{table}/{id}/{back?}', function(Request $request, $table, $id, $back = '') {
    // Lista blanca de tablas que puedes editar
    $allowed = ['clientes','productos','movimientos','detalles_factura_venta'];
    if (! in_array($table, $allowed)) {
        abort(404);
    }

    // Recupero el registro
    $record = DB::table($table)->where('id', $id)->first();
    if (! $record) {
        abort(404);
    }

    // Columnas editables (excluyendo id y timestamps)
    $columns = array_filter(
        array_keys((array) $record),
        fn($col) => ! in_array($col, ['id','created_at','updated_at'])
    );

    // Paso $back para que el Blade pueda generar el enlace de retorno
    return view('editor', compact('table','id','record','columns','back'));
})->name('editor.edit');

// Procesar actualizaciones
Route::post('editor/{table}/{id}/{back?}', function(Request $request, $table, $id, $back = '') {
    $allowed = ['clientes','productos','movimientos','detalles_factura_venta'];
    if (! in_array($table, $allowed)) {
        abort(404);
    }

    // Recupero columnas dinámicas
    $record = DB::table($table)->where('id', $id)->first();
    if (! $record) {
        abort(404);
    }
    $columns = array_filter(
        array_keys((array) $record),
        fn($col) => ! in_array($col, ['id','created_at','updated_at'])
    );

    // Validación genérica
    $rules = [];
    foreach ($columns as $col) {
        $rules[$col] = 'nullable|string|max:255';
    }
    $data = $request->validate($rules);

    // Actualizo registro
    DB::table($table)
      ->where('id', $id)
      ->update($data);

    return redirect()
        ->route('editor.edit', ['table' => $table, 'id' => $id, 'back' => $back])
        ->with('status', 'Registro actualizado.');
})->name('editor.update');
