<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RutaCoordenada;

class RutaCoordenadaSeeder extends Seeder
{
    public function run(): void
    {
        RutaCoordenada::truncate();

        $coordenadas = [

            [-20.0381000, -63.5185000],
            [-20.0378000, -63.5189000],
            [-20.0375000, -63.5193000],
            [-20.0372000, -63.5197000],
            [-20.0370000, -63.5200000],
            [-20.0366000, -63.5207000],
            [-20.0362000, -63.5213000],
            [-20.0358000, -63.5220000],

        ];

        foreach ($coordenadas as $orden => $coord) {

            RutaCoordenada::create([
                'idruta' => 1,
                'orden' => $orden + 1,
                'latitud' => $coord[0],
                'longitud' => $coord[1],
            ]);

        }
    }
}