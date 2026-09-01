<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;

class RutaSeeder extends Seeder
{
    public function run(): void
    {
        Ruta::truncate();

        Ruta::create([
            'nombreruta' => 'Ruta Centro',
            'origen'     => 'Terminal',
            'destino'    => 'Mercado Central',
            'distancia'  => 8.50,
            'idlinea'    => 1
        ]);
    }
}