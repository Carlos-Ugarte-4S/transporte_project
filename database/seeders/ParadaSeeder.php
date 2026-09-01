<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parada;

class ParadaSeeder extends Seeder
{
    public function run(): void
    {
        Parada::truncate();

        Parada::create([
            'nombre' => 'Terminal',
            'ubicacion' => 'Terminal de Buses',
            'latitud' => -20.0381000,
            'longitud' => -63.5185000,
            'orden' => 1,
            'idruta' => 1,
        ]);

        Parada::create([
            'nombre' => 'Plaza Principal',
            'ubicacion' => 'Centro de Camiri',
            'latitud' => -20.0370000,
            'longitud' => -63.5200000,
            'orden' => 2,
            'idruta' => 1,
        ]);

        Parada::create([
            'nombre' => 'Mercado Central',
            'ubicacion' => 'Mercado',
            'latitud' => -20.0358000,
            'longitud' => -63.5220000,
            'orden' => 3,
            'idruta' => 1,
        ]);
    }
}