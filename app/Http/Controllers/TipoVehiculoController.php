<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TipoTransporte;

class TipoVehiculoController extends Controller
{

   public function index()
    {

        return Inertia::render(
            'Tipo_Transportes/Index',
            [
                'tipo_transportes'=>TipoTransporte::orderBy('nombre')
                    ->get()
            ]
        );

    }

    public function create(){

    return Inertia::render('Tipo_Transportes/Create');
}

public function store(Request $request)
{
    $request->validate([

        'nombre'=>'required|max:100',

        'capacidad'=>'required|integer|min:1',

    ]);


   TipoTransporte::create([

        'nombre'=>$request->nombre,

        'capacidad'=>$request->capacidad,

        'estado' => true
    ]);



    return redirect()
        ->route('tipo_transportes.index')
        ->with(
            'success',
            'Tipo de Vehículo creado correctamente'
        );

}

public function edit(TipoTransporte $tipo_transporte)
    {
        return Inertia::render(
            'Tipo_Transportes/Edit',
            [
                'tipo_transporte'=>$tipo_transporte
            ]
        );
    }


 public function update(Request $request, TipoTransporte $tipo_transporte)
{

    $request->validate([

        'nombre'=>'required|max:100',

        'capacidad'=>'required|integer|min:1'

    ]);



    $tipo_transporte->update([

        'nombre'=>$request->nombre,

        'capacidad'=>$request->capacidad,

        'estado'=>$request->boolean('estado')

    ]);



    return redirect()
        ->route('tipo_transportes.index')
        ->with(
            'success',
            'Tipo de Transporte actualizado correctamente'
        );

}
}
