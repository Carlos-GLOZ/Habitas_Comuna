<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_presi extends Model
{
    use HasFactory;


    protected $fillable = [
        'msg',
        'fecha',
        'comunidad_id',
        'user_id',
        'user_id_recibido',
        'created_at',
        'updated_at'
    ];
}
