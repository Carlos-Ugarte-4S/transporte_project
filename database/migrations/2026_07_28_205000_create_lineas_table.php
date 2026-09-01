<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          Schema::create('lineas', function (Blueprint $table) {

        $table->id('idlinea');

        $table->string('nombre', 100);

        $table->string('contacto', 100);

        $table->unsignedBigInteger('idsindicato');

        $table->timestamps();

        $table->index('nombre');

        $table->enum('estado', [
          'activo',
          'inactivo'
        ])->default('activo');

        $table->foreign('idsindicato')
            ->references('idsindicato')
            ->on('sindicatos')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineas');
    }
};
