<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->increments('id_vehiculo');
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->integer('anio');
            $table->decimal('precio', 10, 2);
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('id_vendedor');
            $table->unsignedInteger('id_ubicacion')->nullable(); // FK agregada - un vehículo está ubicado en una ubicación
            $table->enum('estado', ['disponible', 'vendido'])->default('disponible');
            $table->dateTime('fecha_publicacion')->useCurrent();

            // Relación con usuarios (vendedor)
            $table->foreign('id_vendedor')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Relación con ubicaciones
            $table->foreign('id_ubicacion')
                  ->references('id_ubicacion')
                  ->on('ubicaciones')
                  ->onDelete('set null')   // si se borra la ubicación, el vehículo no se elimina
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropForeign(['id_vendedor']);
            $table->dropForeign(['id_ubicacion']);
        });

        Schema::dropIfExists('vehiculos');
    }
};