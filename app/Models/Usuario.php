<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{

    protected $table='usuarios';


    protected $primaryKey='ci';


    public $incrementing=false;


    protected $keyType='string';

    protected $fillable=[

        'ci',
        'correo',
        'contrasena',
        'estado'

    ];

    protected $casts=[

        'estado'=>'boolean'

    ];

    protected $hidden=[

        'contrasena'

    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class,'ci');
    }

     public function getAuthPassword()
    {
        return $this->contrasena;
    }

}