<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RutaSegmento extends Model
{
    protected $table = 'ruta_segmentos';

    protected $primaryKey = 'idsegmento';


    protected $fillable = [
        'idruta',
        'orden',
        'tipo',
        'geometria'
    ];


    protected $casts = [
        'geometria' => 'array'
    ];



    public function ruta()
    {
        return $this->belongsTo(
            Ruta::class,
            'idruta','idruta'
        );
    }
}
