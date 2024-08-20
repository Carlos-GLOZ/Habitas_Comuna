<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegar extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'nombre_receptor',
        'receptor_id',
        'emisor_id',
        'encuesta_id'
    ];
}
