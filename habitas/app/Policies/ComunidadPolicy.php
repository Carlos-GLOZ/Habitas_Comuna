<?php

namespace App\Policies;

use App\Models\Comunidad;
use App\Models\User;

class ComunidadPolicy
{
    /**
     * Devuelve si el usuario es el creador de la comunidad dada
     */
    // public function creador(User $user, Comunidad $comunidad)
    // {
    //     return $comunidad->creador_id == $user->id;
    // }

    /**
     * Devuelve si el usuario es el presidente de la comunidad dada
     */
    public function presidente(User $user, Comunidad $comunidad)
    {
        return $comunidad->presidente_id == $user->id;
    }

    /**
     * Devuelve si el usuario está blacklisteado por una comunidad
     */
    public function unirse(User $user, Comunidad $comunidad)
    {
        $existe = $comunidad->blacklist()->where(['user_id' => $user->id])->first();

        // Si no existe ningún registro de la blacklist que bloquee a este usuario de esta comunidad, autorizar
        if ($existe == null) {
            return true;
        }

        return false;
    }

    /**
     * Devuelve si el usuario es miembro de una comunidad
     */
    public function miembro(User $user, Comunidad $comunidad)
    {
        $results = $comunidad->miembros()->where(['user_id' => $user->id])->first();

        if ($results == null) {
            return false;
        }
        return true;
    }
}
