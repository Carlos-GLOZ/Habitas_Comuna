<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'creador_id',
        'presidente_id',
        'vicepresidente_id',
        'nombre',
        'codigo',
        'correo',
        'meet'
    ];

    /**
     * Los atributos a los que solo el presidente de una comunidad tendría que tener acceso
     */
    public $atributosPrivados = [
        'codigo',
        'correo',
        'miembros'
    ];

    /**
     * Devolver los atributos de la comunidad ofucados
     */
    public function ofuscar()
    {
        $array_valores = $this->toArray();

        foreach ($array_valores as $key => $value) {
            if (in_array($key, $this->atributosPrivados)) {
                $array_valores[$key] = '-';
            }
        }

        return $array_valores;
    }

    /**
     * Los miembros de la comunidad
     */
    public function miembros()
    {
        return $this->belongsToMany(User::class, 'usuario_comunidads');
    }



    /**
     * Los usuarios blacklisteados de la comunidad
     */
    public function blacklist()
    {
        return $this->belongsToMany(User::class, 'blacklists');
    }

    /**
     * El presidente de la comunidad
     */
    public function presidente()
    {
        return $this->belongsTo(User::class, 'presidente_id', 'id');
    }

    /**
     * El vicepresidente de la comunidad
     */
    public function vicepresidente()
    {
        return $this->belongsTo(User::class, 'vicepresidente_id', 'id');
    }

    /**
     * Las incidencias de la counidad
     */
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    /**
     * Los anuncios de la counidad
     */
    public function anuncios()
    {
        return $this->hasMany(Anuncio::class);
    }

    /**
     * Los eventos de la counidad
     */
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }

    /**
     * Las encuestas de la comunidad
     */
    public function encuestas()
    {
        return $this->hasMany(Encuesta::class);
    }

    /**
     * Los pagos de la comunidad
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    /**
     * Los mensajes del presidente con miembros de la comunidad
     */
    public function chats_presidente()
    {
        return $this->hasMany(Chat_presi::class);
    }

    /**
     * Las votaciones que han sido delegadas de esta comunidad
     */
    public function votaciones_delegadas()
    {
        return $this->hasManyThrough(Delegar::class, Encuesta::class);
    }

    /**
     * Los seguros de esta comunidad
     */
    public function seguros()
    {
        return $this->hasMany(Seguro::class);
    }

    /**
     * Los modulos de esta comunidad
     */
    public function modulos()
    {
        return $this->belongsToMany(Modulo::class);
    }

    /**
     * Los servicios de esta comunidad
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    /**
     * Los archivos de esta comunidad
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }
}
