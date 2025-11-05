<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class PreferenciasControllerTest extends TestCase
{
    public function test_puede_cambiar_el_tema_y_se_persiste_en_cookies()
    {
        $response = $this->post(route('preferencias.update'), [
            'tema' => 'oscuro',
            'moneda' => 'EUR',
            'paginacion' => 6,
            'sesionId' => 'abc123',
        ]);

        $response->assertRedirect(route('preferencias.edit', ['sesionId' => 'abc123']));
        $response->assertSessionHas('success', 'Preferencias guardadas correctamente.');

        $response->assertCookie('tema', 'oscuro');
    }

    public function test_puede_cambiar_paginacion_y_efecto_en_listado()
    {
        $this->withCookie('paginacion', 12);

        $response = $this->get(route('muebles.index'));

        $response->assertStatus(200);

        $response->assertViewHas('muebles', function ($muebles) {
            return count($muebles) <= 12;
        });
    }

    public function test_si_no_hay_cookie_de_paginacion_usa_el_valor_por_defecto()
    {
        $response = $this->get(route('muebles.index'));

        $response->assertStatus(200);

        $response->assertViewHas('muebles', function ($muebles) {
            return count($muebles) <= 6;
        });
    }
}
