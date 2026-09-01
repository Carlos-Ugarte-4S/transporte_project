// database/migrations/[timestamp]_create_paradas_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paradas', function (Blueprint $table) {
            $table->id('idparada');
            $table->string('nombre', 100);
            $table->string('ubicacion', 255)->nullable();
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->unsignedInteger('orden');
            $table->unsignedBigInteger('idruta');
            $table->timestamps();

            $table->foreign('idruta')
                ->references('idruta')
                ->on('rutas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paradas');
    }
};