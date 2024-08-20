<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'nombre',
        'descripcion',
        'comunidad_id'
    ];
    /**
     * La comunidad a la que pertenece el anuncio
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }
}
