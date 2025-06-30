<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalles_factura_venta', function (Blueprint $table) {
            $table->id();
            // FK a la tabla movimientos (facturas)
            $table->foreignId('id_factura')
                  ->constrained('movimientos')
                  ->cascadeOnDelete();
            // FK a la tabla productos
            $table->foreignId('id_producto')
                  ->constrained('productos')
                  ->cascadeOnDelete();
            // Datos de la línea
            $table->integer('cantidad');
            $table->decimal('preciou', 10, 2);
            $table->decimal('descuento', 5, 2);
            $table->decimal('monto', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_factura_venta');
    }
};
