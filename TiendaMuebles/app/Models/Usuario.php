<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;
use App\Enums\RolUsuario;

class Usuario
{
    private int $id;
    private string $email;
    private string $nombre;
    private RolUsuario $rol;
    private string $password;

    public function __construct($id, $email, $nombre, RolUsuario $rol, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->rol = $rol;
        $this->password = $password;
    }

    // ====== Método de datos de Usuarios simulados ======
    private static function datosUsuarios(): array
    {
        return [
            new Usuario(1, 'admin@correo.com', 'juan', RolUsuario::ADMIN, '1234'),
            new Usuario(2, 'paconi@correo.com', 'paconi', RolUsuario::USUARIO, '1234'),
            new Usuario(3, 'gestor@correo.com', 'Marlon', RolUsuario::GESTOR, '1234'),
        ];
    }

    public static function verificarCredenciales($email, $password): ?Usuario
    {
        foreach (self::datosUsuarios() as $usuario) {
            if ($usuario->getEmail() === $email && $usuario->getPassword() === $password) {
                return $usuario;
            }
        }
        return null;
    }

    public static function usuarioActivoSesion($sesionId): ?object
    {
        if ($sesionId !== null) {
            $listaUsuariosActivos = Session::get('usuarios');
            if ($listaUsuariosActivos && isset($listaUsuariosActivos[$sesionId])) {
                return json_decode($listaUsuariosActivos[$sesionId]);
            }
        }
        return null;
    }

    // Getters y Setters
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getRol(): RolUsuario
    {
        return $this->rol;
    }

    public function setRol(RolUsuario $rol): self
    {
        $this->rol = $rol;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }
}
