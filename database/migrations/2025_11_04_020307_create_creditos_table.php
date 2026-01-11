<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->id('id_credito');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_venta');
            $table->decimal('monto_total', 12, 2);
            $table->decimal('saldo_pendiente', 12, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('estado', ['activo','liquidado','vencido'])->default('activo');
            $table->timestamps();

            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_venta')->references('id_venta')->on('ventas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};
