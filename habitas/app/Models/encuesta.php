<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estado',
        'comunidad_id'
    ];

    /**
     * La comunidad a la que pertenece la encuesta
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }

    /**
     * Los usuarios que han participado en la encuesta
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_encuestas');
    }

    /**
     * Las preguntas que contiene esta encuesta
     */
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    /**
     * Las opciones de las preguntas que contiene esta encuesta
     */
    public function opciones()
    {
        return $this->hasManyThrough(Opcion::class, Pregunta::class);
    }

    public function opcion()
    {
        return $this->hasMany(Opcion::class);
    }

    /**
     * Las votaciones que han sido delegadas de esta encuesta
     */
    public function votaciones_delegadas()
    {
        return $this->hasMany(Delegar::class);
    }
}
