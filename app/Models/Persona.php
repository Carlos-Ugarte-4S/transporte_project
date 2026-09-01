<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table='personas';

    protected $primaryKey='ci';

    public $incrementing=false;

    protected $keyType='string';

    protected $fillable=[

        'ci',
        'nombre',
        'apellido',
        'telefono',
        'sexo',
        'licencia',
        'tipoc',
        'tipop',
        'tipoa',
        'estado'

    ];

    protected $casts=[

        'tipoc'=>'boolean',
        'tipop'=>'boolean',
        'tipoa'=>'boolean',
        'estado'=>'boolean'

    ];

    public function usuario()
    {
        return $this->hasOne(Usuario::class,'ci');
    }

}