<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario_comunidad extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comunidad_id'
    ];

    public $timestamps = false;
}
