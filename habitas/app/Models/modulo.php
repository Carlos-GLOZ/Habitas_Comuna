<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'precio'
    ];
    /**
     * La comunidad a la que pertenece el modulo
     */
    public function comunidad()
    {
        return $this->belongsToMany(Comunidad::class);
    }
}
