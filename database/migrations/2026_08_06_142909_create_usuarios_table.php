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
       Schema::create('usuarios', function (Blueprint $table) {

    $table->string('ci',10)->primary();

    $table->string('correo',255)->unique();

    $table->string('contrasena');

    $table->boolean('estado')->default(true);

    $table->timestamps();

    $table->foreign('ci')
        ->references('ci')
        ->on('personas')
        ->cascadeOnUpdate()
        ->restrictOnDelete();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
