<?php

namespace App\Http\Controllers;


use App\Models\Persona;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PersonaController extends Controller
{

    /**
     * Listado de personas
     */
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $estado = $request->estado;

        $query = Persona::query();

        // Buscar por CI, nombre o apellido
       // Buscar por CI, nombre o apellido
  if ($request->filled('buscar')) {

    $buscar = trim($request->buscar);

    $query->where(function ($q) use ($buscar) {

        $q->where('ci', 'ILIKE', "%{$buscar}%")
          ->orWhere('nombre', 'ILIKE', "%{$buscar}%")
          ->orWhere('apellido', 'ILIKE', "%{$buscar}%")
          ->orWhereRaw("nombre || ' ' || apellido ILIKE ?", ["%{$buscar}%"]);

    });

}

    // Estado
    if ($request->estado !== null && $request->estado !== '') {
        $query->where('estado', $request->estado);
    }

        return Inertia::render('Personas/Index', [
          'personas' => $query
           ->orderBy('nombre')
          ->paginate(10)
        ->withQueryString(),

          'filters' => [
             'buscar' => $request->buscar ?? '',
             'estado' => $request->estado ?? ''
        ]
        ]);

    }


    public function create()
    {

        return Inertia::render('Personas/Create');

    }


  public function store(Request $request)
{
    $request->validate([
        'ci' => 'required|unique:personas,ci',
        'nombre' => 'required|max:100',
        'apellido' => 'required|max:100',
        'telefono' => 'nullable|max:20',
        'sexo' => 'required',
        'licencia' => 'nullable|max:20',
    ]);

    Persona::create([
        'ci' => $request->ci,
        'nombre' => $request->nombre,
        'apellido' => $request->apellido,
        'telefono' => $request->telefono,
        'sexo' => $request->sexo,
        'licencia' => $request->licencia,

        'tipoa' => $request->boolean('tipoa'),
        'tipoc' => $request->boolean('tipoc'),
        'tipop' => $request->boolean('tipop'),

        'estado' => true,
    ]);

    return redirect()->route('personas.index');
}

public function cambiarEstado(Persona $persona)
{
    $persona->update([
        'estado' => !$persona->estado
    ]);

    return back();
}

    public function show(Persona $persona)
    {

    }


    public function edit(Persona $persona)
{
    return Inertia::render('Personas/Edit', [
        'persona' => $persona
    ]);
}

    public function update(Request $request, Persona $persona)
{
    $request->validate([

        'nombre' => 'required|max:100',

        'apellido' => 'required|max:100',

        'telefono' => 'nullable|max:20',

        'sexo' => 'required',

        'licencia' => 'nullable|max:20',

    ]);

    $persona->update([

        'nombre' => $request->nombre,

        'apellido' => $request->apellido,

        'telefono' => $request->telefono,

        'sexo' => $request->sexo,

        'licencia' => $request->licencia,

        'tipoa' => $request->boolean('tipoa'),

        'tipoc' => $request->boolean('tipoc'),

        'tipop' => $request->boolean('tipop'),

        'estado' => $request->boolean('estado'),

    ]);

    return redirect()->route('personas.index');
}


    public function destroy()
    {

    }

}