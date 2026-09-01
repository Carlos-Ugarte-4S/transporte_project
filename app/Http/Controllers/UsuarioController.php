<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Persona;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $usuarios = Usuario::with('persona')
            ->orderBy('ci')
            ->get();

        return Inertia::render('Usuarios/Index', [

            'usuarios' => $usuarios

        ]);
    }

    /**
     * Formulario para crear usuario
     */
   public function create()
{
    $personas = Persona::doesntHave('usuario')
        ->where('estado', true)
        ->orderBy('nombre')
        ->get();

    return Inertia::render('Usuarios/Create', [
        'personas' => $personas
    ]);
}

    public function store(Request $request)
{
    $request->validate([

        'ci' => 'required|exists:personas,ci|unique:usuarios,ci',

        'correo' => 'required|email|unique:usuarios,correo',

        'contrasena' => 'required|min:8|confirmed',

    ]);

    Usuario::create([

        'ci' => $request->ci,

        'correo' => $request->correo,

        'contrasena' => bcrypt($request->contrasena),

        'estado' => true,

    ]);

    return redirect()->route('usuarios.index')
        ->with('success', 'Usuario creado correctamente.');
}

    public function show(Usuario $usuario)
    {

    }

  public function edit(Usuario $usuario)
{

    return Inertia::render('Usuarios/Edit', [

        'usuario' => $usuario->load('persona')

    ]);

}

public function update(Request $request, Usuario $usuario)
{

    $request->validate([

        'correo' => 'required|email|unique:usuarios,correo,'.$usuario->ci.',ci',

    ]);


    $usuario->update([

        'correo'=>$request->correo,

        'estado'=>$request->boolean('estado')

    ]);


    return redirect()
        ->route('usuarios.index')
        ->with('success','Usuario actualizado correctamente');

}

public function password(Usuario $usuario)
{

    return Inertia::render('Usuarios/Password', [

        'usuario'=>$usuario->load('persona')

    ]);

}

public function updatePassword(Request $request, Usuario $usuario)
{

    $request->validate([

        'contrasena'=>'required|min:8|confirmed'

    ]);


    $usuario->update([

        'contrasena'=>Hash::make(
            $request->contrasena
        )

    ]);


    return redirect()
        ->route('usuarios.index')
        ->with(
            'success',
            'Contraseña actualizada correctamente'
        );

}

    public function destroy(Usuario $usuario)
    {

    }
}