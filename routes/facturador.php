<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
//cambio nulo
Route::get('/facturador', function() {
    $clientes = DB::table('clientes')->select('*')->get();
    return view('facturador', compact('clientes'));
})->name('facturador.index');

Route::post('/facturador', function(Request $request) {
    // 1) VALIDACIÓN de cabecera y detalle
    $data = $request->validate([
        'cliente_id'        => 'required|array',
        'cliente_id.*'       => 'required|integer|min:1',
        'tipo_movimiento'   => 'required|string|in:venta_contado,venta_credito,pago,devolucion',
        'total'             => 'required|numeric|min:0',
        'producto_id'       => 'required|array|min:1',
        'producto_id.*'     => 'required|integer|exists:productos,id',
        'cantidad'          => 'required|array',
        'cantidad.*'        => 'required|integer|min:1',
        'preciou'           => 'required|array',
        'preciou.*'         => 'required|numeric|min:0',
        'descuento'         => 'required|array',
        'descuento.*'       => 'required|numeric|min:0|max:100',
    ]);

    // Recojo arrays de línea
    $cliente_id   = $request->input('producto_id', []);
    $prod_ids   = $request->input('producto_id', []);
    $cantidades = $request->input('cantidad', []);
    $precios    = $request->input('preciou', []);
    $descuentos = $request->input('descuento', []);
    $lineCount  = count($prod_ids);

    try {
        DB::transaction(function() use($data, $prod_ids, $cantidades, $precios, $descuentos, $lineCount, $cliente_id) {
            // 2) Inserto cabecera y obtengo ID
            $movimientoId = DB::table('movimientos')->insertGetId([
                'cliente_id'      => $cliente_id[0],
                'tipo_movimiento' => $data['tipo_movimiento'],
                'monto'           => $data['total'],
            ]);

            // 3) Inserto detalle de cada línea
            for ($i = 0; $i < $lineCount; $i++) {
                $cantidad = $cantidades[$i];
                $precioU  = $precios[$i];
                $desc     = $descuentos[$i];
                $monto    = round($precioU * $cantidad * (1 - $desc/100), 2);

                DB::table('detalles_factura_venta')->insert([
                    'id_factura'  => $movimientoId,
                    'id_producto' => $prod_ids[$i],
                    'cantidad'    => $cantidad,
                    'preciou'     => $precioU,
                    'descuento'   => $desc,
                    'monto'       => $monto,
                ]);

                // Actualizo stock de producto
                DB::table('productos')
                  ->where('id', $prod_ids[$i])
                  ->decrement('stock', $cantidad);
            }

            // 4) Actualizo saldo del cliente
            DB::table('clientes')
              ->where('id', $cliente_id[0])
              ->decrement('saldo', $data['total']);
        });

        return redirect()->route('facturador.index')
                         ->with('status', 'Factura y detalle guardados.');

    } catch (\Exception $e) {
        return redirect()->route('facturador.index')
                         ->withErrors(['db_error' => 'Error: ' . $e->getMessage()]);
    }
})->name('facturador.store');
