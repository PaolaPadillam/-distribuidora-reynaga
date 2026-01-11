<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',150);
            $table->string('direccion')->nullable();
            $table->string('telefono',20)->nullable();
            $table->string('email',100)->nullable();
            $table->enum('tipo_cliente',['mayoreo','menudeo'])->default('menudeo');
            $table->boolean('maneja_credito')->default(false);
            $table->decimal('limite_credito',10,2)->default(0.00);
            $table->decimal('saldo_actual',10,2)->default(0.00);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
