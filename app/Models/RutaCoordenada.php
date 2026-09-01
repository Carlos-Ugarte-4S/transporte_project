<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaCoordenada extends Model
{
    use HasFactory;

    protected $table = 'ruta_coordenadas';
    protected $primaryKey = 'idcoordenada';

    protected $fillable = [
        'idruta',
        'orden',
        'latitud',
        'longitud'
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'idruta', 'idruta');
    }
}