<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Usuario;
use App\Enums\RolUsuario;

class UsuarioTest extends TestCase
{
    public function test_crear_usuario()
    {
        $usuario = new Usuario(1, 'test@test.com', 'Juan', RolUsuario::ADMIN, '1234');
        $this->assertEquals('Juan', $usuario->getNombre());
        $this->assertEquals('test@test.com', $usuario->getEmail());
        $this->assertEquals(RolUsuario::ADMIN, $usuario->getRol());
    }

    public function test_verificar_credenciales()
    {
        $usuario = Usuario::verificarCredenciales('admin@correo.com', '1234');
        $this->assertNotNull($usuario);
        $this->assertEquals('juan', $usuario->getNombre());

        $usuarioNull = Usuario::verificarCredenciales('no@existe.com', '1234');
        $this->assertNull($usuarioNull);
    }

    public function test_setters()
    {
        $usuario = new Usuario(2, 'email@correo.com', 'Paconi', RolUsuario::USUARIO, 'pass');
        $usuario->setNombre('NuevoNombre')->setEmail('nuevo@correo.com');
        $this->assertEquals('NuevoNombre', $usuario->getNombre());
        $this->assertEquals('nuevo@correo.com', $usuario->getEmail());

        // Si se permite cambiar el rol
        $usuario->setRol(RolUsuario::ADMIN);
        $this->assertEquals(RolUsuario::ADMIN, $usuario->getRol());
    }

    public function test_credenciales_invalidas_devuelven_null()
    {
        $usuario = Usuario::verificarCredenciales('invalido@correo.com', 'wrongpass');
        $this->assertNull($usuario);
    }
}
