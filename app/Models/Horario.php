<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{

    protected $table='horarios';

    protected $primaryKey='idhorario';

    protected $fillable=[

        'horaini',
        'horafin',
        'dia',
        'idturno',
        'estado'

    ];

    protected $casts=[

        'estado'=>'boolean'

    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class,'idturno');
    }

}