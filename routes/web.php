<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SindicatoController;
use App\Http\Controllers\LineaController;
use App\Http\Controllers\TipoVehiculoController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\HorarioVehiculoController;
use App\Http\Controllers\RutaController;

Route::get('/', function () {

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard',
[DashboardController::class,'index']) ->middleware('auth')
->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function(){

    Route::resource(
        'personas',
        PersonaController::class
    );

    Route::resource(
        'usuarios'
    , UsuarioController::class);
});

Route::patch(
    '/personas/{persona}/estado',
    [PersonaController::class, 'cambiarEstado']
)->name('personas.estado');

Route::get(
    'usuarios/{usuario}/password',
    [UsuarioController::class,'password']
)
->name('usuarios.password');


Route::put(
    'usuarios/{usuario}/password',
    [UsuarioController::class,'updatePassword']
)
->name('usuarios.updatePassword');

//Sindicado
Route::resource(
    'sindicatos',
    SindicatoController::class
);

Route::resource('lineas', LineaController::class);

Route::resource('tipo_transportes', TipoVehiculoController::class);

Route::resource('vehiculos', VehiculoController::class);

Route::resource('vehiculo_horarios', HorarioVehiculoController::class);


Route::middleware(['auth'])->group(function () {

        Route::get('/rutas', [RutaController::class, 'index'])
                ->name('rutas.index');

        Route::get('/rutas/create', [RutaController::class, 'create'])
                            ->name('rutas.create');

        Route::post('/rutas/guardar', [RutaController::class, 'guardar'])
                                        ->name('rutas.guardar');

        Route::post('/rutas/calcular', [RutaController::class, 'calcularRuta'])
              ->name('rutas.calcular');

        Route::get('/rutas/{id}', [RutaController::class, 'show'])
                                    ->name('rutas.show');

        Route::get('/rutas/{id}/editar', [RutaController::class, 'editor'])
                                 ->name('rutas.editor');

          Route::delete('/rutas/{id}', [RutaController::class, 'destroy'])
        ->name('rutas.destroy');
                                    });


