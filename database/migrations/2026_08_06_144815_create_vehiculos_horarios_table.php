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
       Schema::create('vehiculo_horarios', function (Blueprint $table) {

    $table->id('idvehiculohorario');

    $table->unsignedBigInteger('idvehiculo');

    $table->unsignedBigInteger('idhorario');

    $table->boolean('estado')->default(true);

    $table->timestamps();

    $table->foreign('idvehiculo')
        ->references('idvehiculo')
        ->on('vehiculos');

    $table->foreign('idhorario')
        ->references('idhorario')
        ->on('horarios');

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos_horarios');
    }
};
