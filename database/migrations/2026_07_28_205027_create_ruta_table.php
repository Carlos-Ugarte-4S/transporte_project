// database/migrations/[timestamp]_create_rutas_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id('idruta');
            $table->string('nombreruta', 100);
            $table->string('origen', 100);
            $table->string('destino', 100);
            $table->decimal('distancia', 10, 2)->default(0);
            $table->unsignedBigInteger('idlinea');
            $table->timestamps();

            $table->enum('estado', [
              'activo',
              'inactivo'
            ])->default('activo');

            $table->foreign('idlinea')
                ->references('idlinea')
                ->on('lineas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};