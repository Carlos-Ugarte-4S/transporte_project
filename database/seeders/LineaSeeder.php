<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Linea;

class LineaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Linea::truncate();

        Linea::create([
            'nombre' => 'Línea A',
            'contacto' => '70000001',
            'idsindicato' => 1
        ]);

        Linea::create([
            'nombre' => 'Línea B',
            'contacto' => '70000002',
            'idsindicato' => 1
        ]);

        Linea::create([
            'nombre' => 'Línea C',
            'contacto' => '70000003',
            'idsindicato' => 2
        ]);

        Linea::create([
            'nombre' => 'Línea D',
            'contacto' => '70000004',
            'idsindicato' => 3
        ]);
    }
}