<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sindicato extends Model
{
    protected $table = 'sindicatos';

    protected $primaryKey = 'idsindicato';


    protected $fillable = [
        'nombre',
        'fundacion',
        'estado'
    ];

      protected $casts=[

         'fundacion'=>'date',
        'estado'=>'boolean'

    ];

        protected $appends=[
        'fundacion_formateada'
    ];


    public function getFundacionFormateadaAttribute()
    {

        return $this->fundacion
            ? $this->fundacion->format('d/m/Y')
            : null;

    }

    public function lineas()
    {
        return $this->hasMany(
            Linea::class,
            'idsindicato',
            'idsindicato'
        );
    }
}