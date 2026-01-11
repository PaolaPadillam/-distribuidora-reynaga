<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_credito')->nullable();
            $table->date('fecha_pago');
            $table->decimal('monto_pago', 12, 2);
            $table->enum('metodo_pago', ['efectivo','transferencia','tarjeta'])->default('efectivo');
            $table->string('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_credito')->references('id_credito')->on('creditos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
