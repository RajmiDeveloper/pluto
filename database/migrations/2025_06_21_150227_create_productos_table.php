<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 100);
            $table->integer('stock')->default(0);
            $table->decimal('precio', 10, 2)
                ->default(0);
        });
}



    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
