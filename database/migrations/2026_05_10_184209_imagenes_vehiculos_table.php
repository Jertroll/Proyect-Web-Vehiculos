<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes_vehiculo', function (Blueprint $table) {
            $table->increments('id_imagen');
            $table->unsignedInteger('id_vehiculo');
            $table->string('url_imagen', 255);
            $table->string('descripcion', 255)->nullable();
            $table->integer('orden')->default(0);
            $table->dateTime('fecha_subida')->useCurrent();

            // Relaciones
            $table->foreign('id_vehiculo')
                  ->references('id_vehiculo')
                  ->on('vehiculos')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('imagenes_vehiculo', function (Blueprint $table) {
            $table->dropForeign(['id_vehiculo']);
        });
        Schema::dropIfExists('imagenes_vehiculo');
    }
};