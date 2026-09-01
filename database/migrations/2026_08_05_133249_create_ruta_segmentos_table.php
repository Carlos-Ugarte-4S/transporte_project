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

        Schema::create('ruta_segmentos', function (Blueprint $table) {

            $table->id('idsegmento');

            $table->unsignedBigInteger('idruta');
            // orden del segmento
            $table->integer('orden');
            // origen del segmento
            $table->enum('tipo',[
                'osrm',
                'manual'
            ]);
            // puntos lat lng
            $table->json('geometria');
            $table->timestamps();

            $table->foreign('idruta')
                ->references('idruta')
                ->on('rutas')
                ->cascadeOnDelete();

        });

    }
   
    public function down(): void
    {
        Schema::dropIfExists('ruta_segmentos');
    }
};
