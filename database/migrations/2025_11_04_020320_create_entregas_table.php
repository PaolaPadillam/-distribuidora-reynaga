<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id('id_entrega');
            $table->unsignedBigInteger('id_ruta')->nullable();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_venta');
            $table->date('fecha');
            $table->enum('estado', ['pendiente','entregado','cancelado'])->default('pendiente');
            $table->string('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_ruta')->references('id')->on('rutas')->onDelete('set null');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_venta')->references('id_venta')->on('ventas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
