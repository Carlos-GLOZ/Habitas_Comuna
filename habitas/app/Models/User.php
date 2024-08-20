<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'apellidos',
        'password',
        'visible',
        'language',
        'dark'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // public function comunidades()
    // {
    //     return $this->belongsToMany(Comunidad::class);
    // }
    /**
     * Las comunidades a las que pertenece el usuario
     */
    public function comunidades()
    {
        return $this->belongsToMany(Comunidad::class, 'usuario_comunidads');
    }

    /**
     * Las comunidades que preside el usuario
     */
    public function comunidades_presididas()
    {
        return $this->hasMany(Comunidad::class, 'presidente_id', 'id');
    }

    /**
     * Las comunidades que vicepreside el usuario
     */
    public function comunidades_vicepresididas()
    {
        return $this->hasMany(Comunidad::class, 'vicepresidente_id', 'id');
    }

    /**
     * Las comunidades creadas por el usuario
     */
    // public function comunidades_creadas()
    // {
    //     return $this->hasMany(Comunidad::class, 'creador_id', 'id');
    // }

    /**
     * Las incidencias creadas por el usuario
     */
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'autor_id', 'id');
    }

    /**
     * Las encuestas en las que participa este usuario
     */
    public function encuestas()
    {
        return $this->belongsToMany(Encuesta::class, 'encuesta_user');
    }

    /**
     * Las comunidades en las que el usuario está blacklisteado
     */
    public function blacklists()
    {
        return $this->belongsToMany(Comunidad::class, 'blacklists');
    }

    /**
     * Las votaciones delegadas a otros usuarios por parte de este usuario
     */
    public function votaciones_delegadas()
    {
        return $this->hasMany(Delegar::class, 'emisor_id', 'id');
    }

    /**
     * Las votaciones recibidas de otros usuarios por parte de este usuario
     */
    public function votaciones_recibidas()
    {
        return $this->hasMany(Delegar::class, 'receptor_id', 'id');
    }

    /**
     * Los pagos efectuados por este usuario
     */
    public function pagos_efectuados()
    {
        return $this->belongsToMany(Pago::class, 'user_pagos');
    }

    /**
     * Los pagos pendientes de este usuario
     */
    public function pagos_pendientes()
    {
        $id_comunidad = Session::get('comunidad');

        $pagos_comunidad = Pago::where(['comunidad_id' => $id_comunidad])->get();

        $pagos_usuario = $this->pagos_efectuados()->where(['comunidad_id' => $id_comunidad])->get();

        $pagos_pendientes = $pagos_comunidad->diff($pagos_usuario);

        return $pagos_pendientes;
    }
}
