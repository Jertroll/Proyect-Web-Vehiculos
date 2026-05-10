<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->increments('id_ubicacion');
            $table->string('ciudad', 100);
            $table->string('pais', 100);
            $table->string('direccion', 255)->nullable();
            $table->decimal('latitud', 10, 6)->nullable();
            $table->decimal('longitud', 10, 6)->nullable();
            $table->string('codigo_postal', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};