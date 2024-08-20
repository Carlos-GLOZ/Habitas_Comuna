<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $fillable = [
        'comunidad_id',
        'fecha_ini',
        'fecha_fin',
        'nombre',
        'descripcion',
        'tipo'
    ];
    
    /**
     * La comunidad a la que pertenece el evento
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }

    /**
     * Las incidencias relacionadas a este evento
     */
    public function incidencias()
    {
        return $this->belongsToMany(Incidencia::class, 'evento_incidencias');
    }
}
