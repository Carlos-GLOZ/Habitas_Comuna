<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    
    /**
     * La comunidad a la que pertenece el pago
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }
    
    /**
     * El usuario asociado al pago
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_pagos');
    }
}
