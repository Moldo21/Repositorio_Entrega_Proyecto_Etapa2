<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Categoria;

class CategoriaTest extends TestCase
{
    public function test_crear_categoria()
    {
        $categoria = new Categoria(1, 'Sillas', 'Comodidad');

        $this->assertEquals(1, $categoria->getId());
        $this->assertEquals('Sillas', $categoria->getNombre());
        $this->assertEquals('Comodidad', $categoria->getDescripcion());
    }

    public function test_json_serialize()
    {
        $categoria = new Categoria(2, 'Mesas', 'Elegantes');

        $json = json_encode($categoria);
        $data = json_decode($json, true);

        $this->assertEquals('Mesas', $data['nombre']);
        $this->assertEquals('Elegantes', $data['descripcion']);
    }

    public function test_buscar_por_id_valido()
    {
        $categoria = Categoria::buscarPorId(1);

        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals(1, $categoria->getId());
    }

    public function test_buscar_por_id_invalido()
    {
        $categoria = Categoria::buscarPorId(999);
        $this->assertNull($categoria);
    }

    public function test_datos_categorias()
    {
        $categorias = Categoria::datosCategorias();

        $this->assertIsArray($categorias);
        $this->assertNotEmpty($categorias);
        $this->assertInstanceOf(Categoria::class, $categorias[0]);

        $this->assertArrayHasKey('nombre', $categorias[0]->jsonSerialize());
        $this->assertArrayHasKey('descripcion', $categorias[0]->jsonSerialize());
    }
}
