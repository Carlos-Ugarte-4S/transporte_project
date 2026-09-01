<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tablas (orden correcto por foreign keys)
        Usuario::truncate();
        Persona::truncate();

        // Registrar personas
        $personas = [
            [
                'ci' => '123456',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'telefono' => '123456789',
                'sexo' => 'M',
                'licencia' => 'ABC123456',
                'tipoc' => true,
                'tipop' => false,
                'tipoa' => false,
                'estado' => true
            ],
            [
                'ci' => '654321',
                'nombre' => 'María',
                'apellido' => 'García',
                'telefono' => '987654321',
                'sexo' => 'F',
                'licencia' => 'XYZ987654',
                'tipoc' => false,
                'tipop' => true,
                'tipoa' => false,
                'estado' => true
            ],
            [
                'ci' => '789012',
                'nombre' => 'Carlos',
                'apellido' => 'López',
                'telefono' => '456789123',
                'sexo' => 'M',
                'licencia' => 'DEF456789',
                'tipoc' => false,
                'tipop' => false,
                'tipoa' => true,
                'estado' => true
            ]
        ];

        foreach ($personas as $persona) {
            Persona::create($persona);
        }

        // Registrar usuarios (relacionados con las personas)
        $usuarios = [
            [
                'ci' => '123456',
                'correo' => 'juan.perez@example.com',
                'contrasena' => Hash::make('password123'),
                'estado' => true
            ],
            [
                'ci' => '654321',
                'correo' => 'maria.garcia@example.com',
                'contrasena' => Hash::make('password456'),
                'estado' => true
            ],
            [
                'ci' => '789012',
                'correo' => 'carlos.lopez@example.com',
                'contrasena' => Hash::make('password789'),
                'estado' => true
            ]
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}