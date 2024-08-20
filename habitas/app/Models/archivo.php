<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'extension',
        'tipo',
        'comunidad_id'
    ];
    /**
     * La comunidad a la que pertenece el archivo
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }
}
