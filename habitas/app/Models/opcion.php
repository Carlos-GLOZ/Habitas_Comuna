<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'opcion',
        'pregunta_id',
        'votos'
    ];

    /**
     * La pregunta a la que pertenece esta opción
     */
    public function preguntas()
    {
        return $this->belongsTo(Encuesta::class);
    }
    public function opcions()
    {
        return $this->hasMany(Opcion::class);
    }
}
