<?php

namespace App\Enums;

enum RolUsuario: string
{
    case USUARIO = 'usuario';
    case ADMIN = 'admin';
    case GESTOR = 'gestor';

    public function descripcion(): string
    {
        return match ($this) {
            self::USUARIO => 'Usuario estándar con permisos limitados',
            self::GESTOR => 'Gestor con permisos intermedios',
            self::ADMIN => 'Administrador con todos los permisos',
        };
    }
}
