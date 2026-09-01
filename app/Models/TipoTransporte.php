<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vehiculo;

  

class TipoTransporte extends Model
{

    protected $table='tipo_transportes';

    protected $primaryKey='idtipotransporte';

    protected $fillable=[

        'nombre',
        'capacidad',
        'estado'

    ];

    protected $casts=[

        'estado'=>'boolean'

    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class,'idtipotransporte');
    }

}