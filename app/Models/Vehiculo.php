<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{

    protected $table='vehiculos';

    protected $primaryKey='idvehiculo';

    protected $fillable=[

        'modelo',
        'anio',
        'codigo',
        'idtipotransporte',
        'idlinea',
        'ci',
        'estado'
    ];

    protected $casts=[

        'estado'=>'boolean'

    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class,'ci');
    }

    public function linea()
    {
        return $this->belongsTo(Linea::class,'idlinea','idlinea');
    }

    public function tipotransporte()
    {
        return $this->belongsTo(TipoTransporte::class,'idtipotransporte');
    }

}