<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;

class Turno extends Model
{

    protected $table='turnos';

    protected $primaryKey='idturno';

    protected $fillable=[

        'nombre',
        'estado'

    ];

    protected $casts=[

        'estado'=>'boolean'

    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class,'idturno');
    }

}