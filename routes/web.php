<?php

use Illuminate\Support\Facades\Route;

Route::match(['get','post'], '/', function () {
    return view('welcome');
});
include __DIR__ . '/head.php'; 
require __DIR__ . '/clientes.php';
require __DIR__ . '/productos.php';
require __DIR__ . '/facturador.php';
require __DIR__.'/editor.php';
require __DIR__.'/facturas.php';
require __DIR__.'/visor(incmplt).php';


require __DIR__ . '/clientes.autocomplete.php';
require __DIR__ . '/productos.autocomplete.php';


