<?php
namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Horario;
use App\Models\VehiculoHorario;
use App\Models\Turno;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class HorarioVehiculoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $vehiculo_horarios = VehiculoHorario::with([ 'vehiculo.linea', 'horario.turno', ]) 
        ->orderBy('idvehiculohorario', 'desc')
         ->get(); 
         
         return Inertia::render( 'HorariosVehiculos/Index', 
         [ 'vehiculo_horarios' => $vehiculo_horarios ] );
    }

public function create()
{
    $vehiculos = Vehiculo::with('linea')
        ->where('estado', true)
        ->orderBy('idvehiculo', 'desc')
        ->get();

    $turnos = Turno::where('estado', true)
        ->orderBy('nombre')
        ->get();

    return Inertia::render(
        'HorariosVehiculos/Create',
        [
            'vehiculos' => $vehiculos,
            'turnos' => $turnos,
        ]
    );
}

    public function store(Request $request) 
    { 
        $request->validate([ 'idvehiculo' => 'required|exists:vehiculos,idvehiculo',
         'idturno' => 'required|exists:turnos,idturno',
          'dia' => 'required|string|max:10', 
          'horaini' => 'required|date_format:H:i',
           'horafin' => 'required|date_format:H:i|after:horaini', ]); 
           DB::transaction(function () use ($request) { 

            $horario = Horario::create([ 
                'horaini' => $request->horaini,
                 'horafin' => $request->horafin, 
                 'dia' => $request->dia, 
                 'idturno' => $request->idturno, 
                 'estado' => true, ]); 
               
                 VehiculoHorario::create([ 
                    'idvehiculo' => $request->idvehiculo, 
                    'idhorario' => $horario->idhorario, 
                    'estado' => true, ]); }); 
                    
                    return redirect()
                     ->route('vehiculo_horarios.index') 
                    ->with( 'success', 'Horario de vehículo creado correctamente' ); 
                    
            }


        
public function edit(VehiculoHorario $vehiculoHorario)
{
    // Cargar el registro con sus relaciones
    $vehiculoHorario->load([
        'vehiculo.linea',
        'horario.turno',
    ]);

    // Vehículos activos
    $vehiculos = Vehiculo::with('linea')
        ->where('estado', true)
        ->orderBy('idvehiculo', 'desc')
        ->get();

    // Turnos activos
    $turnos = Turno::where('estado', true)
        ->orderBy('nombre')
        ->get();

    return Inertia::render(
        'HorariosVehiculos/Edit',
        [
            'vehiculo_horario' => $vehiculoHorario,
            'vehiculos' => $vehiculos,
            'turnos' => $turnos,
        ]
    );
}


public function update(Request $request, VehiculoHorario $vehiculoHorario)
{
    $request->validate([
        'idvehiculo' => 'required|exists:vehiculos,idvehiculo',

        'idturno' => 'required|exists:turnos,idturno',

        'dia' => 'required|string|max:10',

        'horaini' => 'required|date_format:H:i',

        'horafin' => 'required|date_format:H:i|after:horaini',

        'estado' => 'required|boolean',
    ]);

    DB::transaction(function () use ($request, $vehiculoHorario) {

        // Actualizar horario
        $vehiculoHorario->horario->update([
            'idturno' => $request->idturno,
            'dia' => $request->dia,
            'horaini' => $request->horaini,
            'horafin' => $request->horafin,
            'estado' => $request->estado,
        ]);

        // Actualizar relación vehículo-horario
        $vehiculoHorario->update([
            'idvehiculo' => $request->idvehiculo,
            'estado' => $request->estado,
        ]);
    });

    return redirect()
        ->route('vehiculo_horarios.index')
        ->with(
            'success',
            'Horario de vehículo actualizado correctamente'
        );
}




}

