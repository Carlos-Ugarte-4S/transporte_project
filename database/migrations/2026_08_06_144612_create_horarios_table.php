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
       Schema::create('horarios', function (Blueprint $table) {

    $table->id('idhorario');

    $table->time('horaini');

    $table->time('horafin');

    $table->string('dia',10);

    $table->unsignedBigInteger('idturno');

    $table->boolean('estado')->default(true);

    $table->timestamps();

    $table->foreign('idturno')
        ->references('idturno')
        ->on('turnos');

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
