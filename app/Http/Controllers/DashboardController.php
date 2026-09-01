<?php

namespace App\Http\Controllers;


use App\Models\Persona;
use App\Models\Vehiculo;
use App\Models\Ruta;
use App\Models\Linea;

use Inertia\Inertia;


class DashboardController extends Controller
{

    public function index()
    {

        return Inertia::render('Dashboard', [

            'estadisticas'=>[

                'personas'=>Persona::count(),

                'rutas'=>Ruta::count(),

                'vehiculos'=>Vehiculo::count(),

                'lineas'=>Linea::count(),

            ]

        ]);

    }

}