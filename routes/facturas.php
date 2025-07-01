<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/facturas', function() {
    $facturas = DB::table('movimientos')->select('*')->get();
    $clientes = DB::table('clientes')->select('*')->get();
    return view('facturas', compact('clientes', 'facturas'));
})->name('facturadas.index');