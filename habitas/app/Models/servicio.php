<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'correo',
        'web',
        'telefono',
        'comunidad_id'
    ];

    /**
     * La comunidad a la que pertenece el servicio
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }
}
