<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'num_polizas',
        'fecha_fin',
        'correo',
        'tel',
        'web',
        'direccion',
        'cuota',
    ];
    
    /**
     * La comunidad a la que pertenece el seguro
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }
}
