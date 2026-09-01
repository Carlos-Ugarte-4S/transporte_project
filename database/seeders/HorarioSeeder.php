<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;
use App\Models\Turno;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $manana = Turno::where('nombre', 'Mañana')->first();
        $tarde = Turno::where('nombre', 'Tarde')->first();
        $noche = Turno::where('nombre', 'Noche')->first();

        Horario::updateOrCreate(
            [
                'horaini' => '06:00:00',
                'horafin' => '12:00:00',
                'dia' => 'Lunes',
                'idturno' => $manana->idturno,
            ],
            [
                'estado' => true,
            ]
        );

        Horario::updateOrCreate(
            [
                'horaini' => '13:00:00',
                'horafin' => '18:00:00',
                'dia' => 'Lunes',
                'idturno' => $tarde->idturno,
            ],
            [
                'estado' => true,
            ]
        );

        Horario::updateOrCreate(
            [
                'horaini' => '18:00:00',
                'horafin' => '22:00:00',
                'dia' => 'Lunes',
                'idturno' => $noche->idturno,
            ],
            [
                'estado' => true,
            ]
        );
    }
}