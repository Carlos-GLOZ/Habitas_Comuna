<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_encuesta extends Model
{
    use HasFactory;

    protected $fillable = [
        'encuesta_id',
        'user_id'
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
