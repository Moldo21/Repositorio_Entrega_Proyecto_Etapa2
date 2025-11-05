<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class CategoriasControllerTest extends TestCase
{
    private function iniciarSesionAdmin()
    {
        $sesionId = 'admin_sesion';
        $usuarioActivo = [
            'id' => 1,
            'email' => 'admin@correo.com',
            'nombre' => 'juan',
            'rol' => 'ADMIN',
            'fecha_ingreso' => now()->toDateTimeString(),
            'sesionId' => $sesionId
        ];
        Session::put('usuarios', [$sesionId => json_encode($usuarioActivo)]);
        Session::put('autorizacion_usuario', true);
        return $sesionId;
    }

    private function iniciarSesionUsuarioNormal()
    {
        $sesionId = 'user_sesion';
        $usuarioActivo = [
            'id' => 2,
            'email' => 'user@correo.com',
            'nombre' => 'usuario',
            'rol' => 'USUARIO',
            'fecha_ingreso' => now()->toDateTimeString(),
            'sesionId' => $sesionId
        ];
        Session::put('usuarios', [$sesionId => json_encode($usuarioActivo)]);
        Session::put('autorizacion_usuario', true);
        return $sesionId;
    }

    public function test_usuario_puede_ver_categorias()
    {
        $sesionId = $this->iniciarSesionUsuarioNormal();
        $response = $this->get(route('categorias.index', ['sesionId' => $sesionId]));
        $response->assertStatus(200);
        $response->assertViewIs('categorias.index');
    }

    public function test_usuario_puede_ver_categoria_especifica()
    {
        $sesionId = $this->iniciarSesionUsuarioNormal();
        $response = $this->get(route('categorias.show', ['id' => 1, 'sesionId' => $sesionId]));
        $response->assertRedirect(route('muebles.index', ['categoria' => 1, 'sesionId' => $sesionId]));
    }

    public function test_usuario_no_autenticado_es_redirigido()
    {
        $response = $this->get(route('categorias.index'));
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['errorCredenciales']);
    }

    public function test_categoria_inexistente_retorna_404()
    {
        $sesionId = $this->iniciarSesionUsuarioNormal();
        $response = $this->get(route('categorias.show', ['id' => 999, 'sesionId' => $sesionId]));
        $response->assertStatus(404);
    }

    public function test_cookie_categoria_es_establecida()
    {
        $sesionId = $this->iniciarSesionUsuarioNormal();
        $categoriaId = 1;

        $response = $this->get(route('categorias.show', ['id' => $categoriaId, 'sesionId' => $sesionId]));

        $response->assertCookie('categoria_id', $categoriaId);
        $response->assertRedirect(route('muebles.index', [
            'categoria' => $categoriaId,
            'sesionId' => $sesionId
        ]));
    }
}
