<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id_pago');
            $table->unsignedInteger('id_compra');
            $table->string('metodo_pago', 50); // 'tarjeta', 'transferencia', 'efectivo'
            $table->decimal('monto', 10, 2);
            $table->dateTime('fecha_pago')->useCurrent();
            $table->string('estado', 50)->default('pendiente'); // 'aprobado', 'rechazado', 'pendiente'

            // Relaciones
            $table->foreign('id_compra')
                  ->references('id_compra')
                  ->on('compras')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['id_compra']);
        });
        Schema::dropIfExists('pagos');
    }
};