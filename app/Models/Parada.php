<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parada extends Model
{
    use HasFactory;

    protected $table = 'paradas';
    protected $primaryKey = 'idparada';

    protected $fillable = [
        'nombre',
        'ubicacion',
        'latitud',
        'longitud',
        'orden',
        'idruta'
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'idruta', 'idruta');
    }
}