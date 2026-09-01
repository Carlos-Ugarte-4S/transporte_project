<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turno;

class TurnoSeeder extends Seeder
{
    public function run(): void
    {
        Turno::updateOrCreate(
            ['nombre' => 'Mañana'],
            ['estado' => true]
        );

        Turno::updateOrCreate(
            ['nombre' => 'Tarde'],
            ['estado' => true]
        );

        Turno::updateOrCreate(
            ['nombre' => 'Noche'],
            ['estado' => true]
        );
    }
}