<?php

namespace Tests\Unit;

use App\Models\Mueble;
use PHPUnit\Framework\TestCase;

class MuebleTest extends TestCase
{
    private Mueble $mueble;

    protected function setUp(): void
    {
        $this->mueble = new Mueble(
            1, 1, 'Silla Test', 'Silla cómoda', 50.0, 10,
            'Madera', '45x45x90', 'Marrón', true, ['img1.jpg']
        );
    }

    public function test_getters()
    {
        $this->assertEquals(1, $this->mueble->getId());
        $this->assertEquals(1, $this->mueble->getCategoria_id());
        $this->assertEquals('Silla Test', $this->mueble->getNombre());
        $this->assertEquals(50.0, $this->mueble->getPrecio());
        $this->assertTrue($this->mueble->getDestacado());
        $this->assertIsArray($this->mueble->getImagenes());
        $this->assertEquals(['img1.jpg'], $this->mueble->getImagenes());
    }

    public function test_json_serialize()
    {
        $json = json_encode($this->mueble);
        $this->assertStringContainsString('"nombre":"Silla Test"', $json);
        $this->assertStringContainsString('"precio":50', $json);
    }

    public function test_buscar_por_id()
    {
        $mueble = Mueble::buscarPorId(1);
        $this->assertNotNull($mueble);
        $this->assertEquals(1, $mueble->getId());

        $muebleNull = Mueble::buscarPorId(999);
        $this->assertNull($muebleNull);
    }

    public function test_filtrar_por_categoria()
    {
        $muebles = Mueble::datosMuebles();
        $sillas = Mueble::filtrarPorCategoria($muebles, 1);

        $this->assertNotEmpty($sillas);
        foreach ($sillas as $m) {
            $this->assertEquals(1, $m->getCategoria_id());
        }
    }

    public function test_filtrar_por_precio()
    {
        $muebles = Mueble::datosMuebles();
        $filtrados = Mueble::filtrarPorRangoPrecio($muebles, 50, 100);

        $this->assertNotEmpty($filtrados);
        foreach ($filtrados as $m) {
            $this->assertGreaterThanOrEqual(50, $m->getPrecio());
            $this->assertLessThanOrEqual(100, $m->getPrecio());
        }
    }

    public function test_ordenar_por_precio()
    {
        $muebles = Mueble::datosMuebles();

        // Ascendente
        $ordenadosAsc = Mueble::ordenarPorPrecio($muebles, 'asc');
        for ($i = 0; $i < count($ordenadosAsc) - 1; $i++) {
            $this->assertLessThanOrEqual(
                $ordenadosAsc[$i + 1]->getPrecio(),
                $ordenadosAsc[$i]->getPrecio(),
                "El mueble en la posición $i no está en orden ascendente por precio"
            );
        }

        // Descendente
        $ordenadosDesc = Mueble::ordenarPorPrecio($muebles, 'desc');
        for ($i = 0; $i < count($ordenadosDesc) - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                $ordenadosDesc[$i + 1]->getPrecio(),
                $ordenadosDesc[$i]->getPrecio(),
                "El mueble en la posición $i no está en orden descendente por precio"
            );
        }
    }
}
