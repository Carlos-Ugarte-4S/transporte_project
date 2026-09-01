<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sindicato;

class SindicatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sindicato::truncate();

        Sindicato::create([
            'nombre' => 'Sindicato 16 de Julio',
            'fundacion' => '1985-07-16'
        ]);

        Sindicato::create([
            'nombre' => 'Sindicato Primero de Mayo',
            'fundacion' => '1992-05-01'
        ]);

        Sindicato::create([
            'nombre' => 'Sindicato San Antonio',
            'fundacion' => '1998-03-20'
        ]);
    }
}