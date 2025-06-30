<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion')->unique();
            $table->string('nombre');
            $table->string('apellido');          // si lo agregaste antes
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('saldo', 10, 2)
                ->default(0);
        });
}



    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
