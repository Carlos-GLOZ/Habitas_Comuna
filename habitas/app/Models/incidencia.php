<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'estado',
        'titulo',
        'descripcion',
        'comunidad_id',
        'autor_id'
    ];



    /**
     * La comunidad a la que pertenece la incidencia
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }

    /**
     * Los eventos relacionadas a esta incidencia
     */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_incidencias');
    }

    /**
     * El autor de la incidencia
     */
    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id', 'id');
    }
}
