<?php

namespace App\Http\Controllers;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Linea;
use App\Models\TipoTransporte;
use App\Models\Persona;

class VehiculoController extends Controller
{
    
  public function index()
{

    $Vehiculos = Vehiculo::with('tipotransporte','linea', 'persona')
        ->orderBy('idvehiculo','desc')
        ->get();
    

    return Inertia::render('Vehiculos/Index', [

        'vehiculos' => $Vehiculos

    ]);

}

 public function create()
    {
        $tipo_transportes = TipoTransporte::where('estado',true)
                        ->get();

        $lineas = Linea::where('estado','activo')
                        ->get();
        
        $personas = Persona::where('estado',true)
                        ->get();


        return inertia(
            'Vehiculos/Create',
            [
                'tipo_transportes'=>$tipo_transportes,
                'lineas'=>$lineas,
                'personas'=>$personas
            ]
        );

    }
public function store(Request $request)
{

    $request->validate([

        'modelo' => 'required|max:100',

        'anio' => 'required|integer|min:1900|max:' . date('Y'),

        'idtipotransporte' => 'required|exists:tipo_transportes,idtipotransporte',

        'idlinea' => 'required|exists:lineas,idlinea',

        'ci' => 'required|exists:personas,ci',

    ]);



      Vehiculo::create([
 
      'modelo'=>$request->modelo,
 
      'anio'=>$request->anio,

      'codigo'=>$request->codigo,

      'idtipotransporte'=>$request->idtipotransporte,

      'idlinea'=>$request->idlinea,

      'ci'=>$request->ci,

      'estado'=>true   ]);


    return redirect()
            ->route('vehiculos.index')
            ->with(
                'success',
                'Vehículo creado correctamente'
            );

}

  public function edit(Vehiculo $vehiculo)
    {
        $tipo_transportes = TipoTransporte::where('estado',true)
                        ->get();

        $lineas = Linea::where('estado','activo')
                        ->get();
        
        $personas = Persona::where('estado',true)
                        ->get();


        return inertia(
            'Vehiculos/Edit',
            [
                'vehiculo'=>$vehiculo,
                'tipo_transportes'=>$tipo_transportes,
                'lineas'=>$lineas,
                'personas'=>$personas
            ]
        );
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
       
   $vehiculo->update([

    'modelo'=>$request->modelo,

    'anio'=>$request->anio,

    'codigo'=>$request->codigo,

    'idtipotransporte'=>$request->idtipotransporte,

    'idlinea'=>$request->idlinea,

    'ci'=>$request->ci,

    'estado'=>$request->estado

]);

 return redirect()
        ->route('vehiculos.index')
        ->with(
            'success',
            'Vehículo actualizado correctamente'
        );

    }


}
