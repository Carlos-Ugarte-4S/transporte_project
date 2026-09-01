// database/migrations/[timestamp]_create_ruta_coordenadas_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruta_coordenadas', function (Blueprint $table) {
            $table->id('idcoordenada');
            $table->unsignedBigInteger('idruta');
            $table->unsignedInteger('orden');
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);

           

            $table->foreign('idruta')
                ->references('idruta')
                ->on('rutas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

             $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruta_coordenadas');
    }
};