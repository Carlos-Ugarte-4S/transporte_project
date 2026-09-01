<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RutaSegmento;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'rutas';
    protected $primaryKey = 'idruta';

    protected $fillable = [
        'nombreruta',
        'origen',
        'destino',
        'distancia',
        'idlinea',
        'tipo_ruta',
        'estado'
    ];

    protected $casts = [
    'distancia' => 'float',
];

    public function linea()
    {
        return $this->belongsTo(Linea::class, 'idlinea', 'idlinea');
    }

    public function paradas()
    {
        return $this->hasMany(Parada::class, 'idruta', 'idruta')
                    ->orderBy('orden');
    }

    public function coordenadas()
    {
        return $this->hasMany(RutaCoordenada::class, 'idruta', 'idruta')
                    ->orderBy('orden');
    }

    public function segmentos()
    {
    return $this->hasMany(RutaSegmento::class, 'idruta','idruta')
    ->orderBy('orden');
    }

    public function getCoordenadasParaMapa()
    {
        return $this->coordenadas->map(function($coord) {
            return [$coord->latitud,
             $coord->longitud];
        })->toArray();
    }
}