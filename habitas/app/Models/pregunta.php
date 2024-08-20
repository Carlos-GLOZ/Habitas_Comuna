<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregunta',
        'votos',
        'encuesta_id'
    ];

    public function encuestas()
    {
        return $this->belongsTo(Encuesta::class);
    }

    /**
     * Las opciones de una encuesta
     */
    public function opciones()
    {
        return $this->hasMany(Opcion::class);
    }

    /**
     * Los usuario que participan en una encuesta
     */
    public function users()
    {
        return $this->belongsToMany(User::class,'user_encuestas');
    }
}
