<?php

namespace App\Http\Controllers;

use App\Models\Sindicato;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Linea;


class LineaController extends Controller
{


  public function index()
{

    $lineas = Linea::with('sindicato')
        ->orderBy('idlinea','desc')
        ->get();
    

    return Inertia::render('Lineas/Index', [

        'lineas' => $lineas

    ]);

}

    public function create()
    {
        $sindicatos = Sindicato::where('estado',true)
                        ->get();

        return inertia(
            'Lineas/Create',
            [
                'sindicatos'=>$sindicatos
            ]
        );

    }
public function store(Request $request)
{

    $request->validate([

        'nombre' => 'required|max:100',

        'contacto' => 'nullable|max:50',

        'idsindicato' => 'required'

    ]);



      Linea::create([
 
      'nombre'=>$request->nombre,
 
      'contacto'=>$request->contacto,

      'idsindicato'=>$request->idsindicato,

      'estado'=>'activo' ]);


    return redirect()
            ->route('lineas.index')
            ->with(
                'success',
                'Línea creada correctamente'
            );

}


    public function destroy(Linea $linea)
    {

    }

    public function edit(Linea $linea)
    {
        $sindicatos = Sindicato::where('estado',true)
                        ->get();

        return inertia(
            'Lineas/Edit',
            [
                'linea'=>$linea,
                'sindicatos'=>$sindicatos
            ]
        );
    }

    public function update(Request $request, Linea $linea)
    {
       
   $linea->update([

    'nombre'=>$request->nombre,

    'contacto'=>$request->contacto,

    'idsindicato'=>$request->idsindicato,

    'estado'=>$request->estado

]);

 return redirect()
        ->route('lineas.index')
        ->with(
            'success',
            'Línea actualizada correctamente'
        );

    }

}