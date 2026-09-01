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
       Schema::create('vehiculos', function (Blueprint $table) {

    $table->id('idvehiculo');

    $table->string('modelo',50);

    $table->integer('anio');

    $table->string('codigo',10);

    $table->string('ci',10);

    $table->unsignedBigInteger('idtipotransporte');

    $table->unsignedBigInteger('idlinea');

    $table->boolean('estado')->default(true);

    $table->timestamps();

    $table->foreign('ci')
        ->references('ci')
        ->on('personas');

    $table->foreign('idtipotransporte')
        ->references('idtipotransporte')
        ->on('tipo_transportes');

    $table->foreign('idlinea')
        ->references('idlinea')
        ->on('lineas');

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
