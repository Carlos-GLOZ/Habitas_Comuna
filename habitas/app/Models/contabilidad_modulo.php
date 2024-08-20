<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contabilidad_modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_ini',
        'fecha_fin',
        'activo',
        'comunidad_id',
        'modulo_id'
    ];

    public $timestamps = false;
}
