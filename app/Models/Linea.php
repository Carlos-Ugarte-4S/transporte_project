<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    use HasFactory;


    protected $table = 'lineas';


    protected $primaryKey = 'idlinea';

    protected $fillable = [
        'nombre',
        'contacto',
        'idsindicato',
        'estado'
    ];



    public function sindicato()
    {
        return $this->belongsTo(
            Sindicato::class,
            'idsindicato',
            'idsindicato'
        );
    }
}