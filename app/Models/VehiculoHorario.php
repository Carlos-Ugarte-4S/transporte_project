<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiculoHorario extends Model
{

    protected $table='vehiculo_horarios';

    protected $primaryKey='idvehiculohorario';

    protected $fillable=[

        'idvehiculo',
        'idhorario',
        'estado'

    ];

    protected $casts=[

        'estado'=>'boolean'

    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class,'idvehiculo');
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class,'idhorario');
    }

}