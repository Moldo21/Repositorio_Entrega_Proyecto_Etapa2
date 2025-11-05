<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class LoginControllerTest extends TestCase
{
    public function test_login_rechazado_con_credenciales_invalidas()
    {
        Session::start();

        $response = $this->post(route('login.store'), [
            'email' => 'user@correo.com',
            'password' => '1234'
        ]);
        $response->assertSessionHasErrors('errorCredenciales');
    }

    public function test_acceso_admin_sin_sesion_o_sin_rol()
    {
        $response = $this->get(route('categorias.index'));
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors();
    }
}
