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
      Schema::create('personas', function (Blueprint $table) {

    $table->string('ci',10)->primary();

    $table->string('nombre',100);

    $table->string('apellido',100);

    $table->string('telefono',20)->nullable();

    $table->enum('sexo',['M','F']);

    $table->string('licencia',20)->nullable();

    $table->boolean('tipoc')->default(false);

    $table->boolean('tipop')->default(false);

    $table->boolean('tipoa')->default(false);

    $table->boolean('estado')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
