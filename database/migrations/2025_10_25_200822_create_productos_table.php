<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('nombre_producto', 100);
            $table->string('descripcion', 200)->nullable();
            $table->decimal('precio_mayoreo', 10, 2);
            $table->decimal('precio_menudeo', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('unidad', 20)->nullable(); // unidad de medida
            $table->unsignedBigInteger('proveedor_id');
            $table->date('fecha_caducidad')->nullable();
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id_proveedor')->on('proveedores')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
