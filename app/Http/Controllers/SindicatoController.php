<?php

namespace App\Http\Controllers;


use App\Models\Sindicato;
use Illuminate\Http\Request;
use Inertia\Inertia;


class SindicatoController extends Controller
{

public function index(Request $request)
{

    $query = Sindicato::query();


    if($request->filled('buscar'))
    {

        $buscar = $request->buscar;


        $query->where(
            'nombre',
            'ILIKE',
            "%{$buscar}%"
        );

    }



    return Inertia::render('Sindicatos/Index',[

        'sindicatos'=>$query
            ->orderBy('nombre')
            ->get(),


        'filters'=>[

            'buscar'=>$request->buscar ?? ''

        ]

    ]);

}



    public function create()
    {

        return Inertia::render(
            'Sindicatos/Create'
        );

    }

    public function store(Request $request)
{

    $request->validate([

        'nombre'=>'required|max:100',

        'fundacion'=>'required|date'

    ]);



    Sindicato::create([

        'nombre'=>$request->nombre,

        'fundacion'=>$request->fundacion,

        'estado'=>true

    ]);



    return redirect()
        ->route('sindicatos.index')
        ->with(
            'success',
            'Sindicato creado correctamente'
        );

}

    public function show(Sindicato $sindicato)
    {

    }



    public function edit(Sindicato $sindicato)
{
    return Inertia::render('Sindicatos/Edit', [

        'sindicato'=>$sindicato

    ]);
}


public function update(Request $request, Sindicato $sindicato)
{

    $request->validate([

        'nombre'=>'required|max:100',

        'fundacion'=>'required|date'

    ]);



    $sindicato->update([

        'nombre'=>$request->nombre,

        'fundacion'=>$request->fundacion,

        'estado'=>$request->boolean('estado')

    ]);



    return redirect()
        ->route('sindicatos.index')
        ->with(
            'success',
            'Sindicato actualizado correctamente'
        );

}



    public function destroy(Sindicato $sindicato)
    {

    }


}