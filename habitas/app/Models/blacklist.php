<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;
    
    /**
     * La comunidad a la que pertenece la incidencia
     */
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class);
    }
    
    /**
     * El usuario blacklisteado
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
