<?php

namespace App\Models;

use JsonSerializable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Mueble implements JsonSerializable
{
    private int $id;
    private int $categoria_id;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private int $stock;
    private string $materiales;
    private string $dimensiones;
    private string $color_principal;
    private bool $destacado;
    private array $imagenes;

    public function __construct(

        int $id,
        int $categoria_id,
        string $nombre,
        string $descripcion,
        float $precio,
        int $stock,
        string $materiales,
        string $dimensiones,
        string $color_principal,
        bool $destacado,
        array $imagenes

        ){

        $this->id = $id;
        $this->categoria_id = $categoria_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->materiales = $materiales;
        $this->dimensiones = $dimensiones;
        $this->color_principal = $color_principal;
        $this->destacado = $destacado;
        $this->imagenes = $imagenes;
    }

    public function jsonSerialize(): array{
        return [
            'id' => $this->id,
            'categoria_id' => $this->categoria_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'materiales' => $this->materiales,
            'dimensiones' => $this->dimensiones,
            'color_principal' => $this->color_principal,
            'destacado' => $this->destacado,
            'imagenes' => $this->imagenes,

        ];
    }

    // ====== Getters públicos ======
    public function getId(): int{
        return $this->id;
    }

    public function getCategoria_id(): int{
        return $this->categoria_id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function getDescripcion(): string{
        return $this->descripcion;
    }

    public function getPrecio(): float{
        return $this->precio;
    }

    public function getStock(): int{
        return $this->stock;
    }

    public function getMateriales(): string{
        return $this->materiales;
    }

    public function getDimensiones(): string{
        return $this->dimensiones;
    }

    public function getColor_principal(): string{
        return $this->color_principal;
    }

    public function getDestacado(): bool{
        return $this->destacado;
    }

    public function getImagenes(): array{
        return $this->imagenes;
    }

    // ====== Método de datos simulados ======
    public static function datosMuebles(): array {
        return [
            // Sillas
            new Mueble(1, 1, 'Silla de madera clásica', 'Silla robusta de roble.', 50.50, 10, 'Madera de roble', '45x45x90 cm', 'Marrón', true, ['silla1_1.jpg', 'silla1_2.jpg', 'silla1_3.jpg']),
            new Mueble(2, 1, 'Silla tapizada moderna', 'Tapizado gris y patas metálicas.', 60.50, 8, 'Metal y tela', '48x50x88 cm', 'Gris', false, ['silla2_1.jpg', 'silla2_2.jpg', 'silla2_3.jpg']),
            new Mueble(3, 1, 'Silla plegable', 'Práctica y ligera, ideal para exteriores.', 39.90, 15, 'Aluminio y plástico', '45x44x85 cm', 'Blanco', false, ['silla3_1.jpg', 'silla3_2.jpg', 'silla3_3.jpg']),

            // Mesas
            new Mueble(4, 2, 'Mesa comedor extensible', 'Perfecta para cenas en familia.', 249.99, 5, 'Madera y metal', '160x90x75 cm', 'Marrón', true, ['mesa1_1.jpg', 'mesa1_2.jpg','mesa1_3.jpg']),
            new Mueble(5, 2, 'Mesa auxiliar redonda', 'Compacta y elegante.', 59.95, 12, 'Madera MDF', '50x50x50 cm', 'Blanca', false, ['mesa2_1.jpg', 'mesa2_2.jpg', 'mesa2_3.jpg']),
            new Mueble(6, 2, 'Mesa de centro industrial', 'Estilo moderno con base metálica.', 119.90, 6, 'Hierro y cristal', '100x60x40 cm', 'Negro', false, ['mesa3_1.jpg', 'mesa3_2.jpg', 'mesa3_3.jpg']),

            // Camas
            new Mueble(7, 3, 'Cama matrimonial de pino', 'Estructura firme y natural.', 299.90, 4, 'Madera de pino', '160x200 cm', 'Blanco', true, ['cama1_1.jpg', 'cama1_2.jpg', 'cama1_3.jpg']),
            new Mueble(8, 3, 'Cama individual moderna', 'Diseño minimalista con cabecero tapizado.', 199.99, 9, 'Madera MDF', '90x190 cm', 'Gris', false, ['cama2_1.jpg', 'cama2_2.jpg', 'cama2_3.jpg']),
            new Mueble(9, 3, 'Cama con cajones', 'Incluye almacenamiento inferior.', 349.99, 3, 'Madera laminada', '150x200 cm', 'Blanco', true, ['cama3_1.jpg', 'cama3_2.jpg', 'cama3_3.jpg']),

            // Estanterías
            new Mueble(10, 4, 'Estantería modular moderna', 'Compartimentos ajustables.', 129.99, 7, 'Metal y melamina', '120x30x180 cm', 'Negro', false, ['estanteria1_1.jpg', 'estanteria1_2.jpg', 'estanteria1_3.jpg']),
            new Mueble(11, 4, 'Estantería de roble', 'Ideal para libros o decoración.', 179.95, 5, 'Madera de roble', '100x35x160 cm', 'Marrón', true, ['estanteria2_1.jpg', 'estanteria2_2.jpg', 'estanteria2_3.jpg']),
            new Mueble(12, 4, 'Estantería flotante', 'Minimalista y resistente.', 69.90, 20, 'MDF', '80x25x20 cm', 'Blanco', false, ['estanteria3_1.jpg', 'estanteria3_2.jpg', 'estanteria3_3.jpg']),
        ];
    }

    public static function buscarPorId(int $id): ?Mueble {
        foreach (Mueble::datosMuebles() as $mueble) {
            if ($mueble->getId() === $id) {
                return $mueble;
            }
        }
        return null;
    }

    public function getCategoriaNombre(){
        $categorias = [
            1 => 'Sillas',
            2 => 'Mesas',
            3 => 'Camas',
            4 => 'Estanterías'
        ];

        return $categorias[$this->categoria_id] ?? 'Sin categoría';
    }

    public static function filtrarPorCategoria(array $muebles, int $categoriaId): array {
        $mueblesCategoria  = [];

        foreach ($muebles as $mueble) {
            if ($mueble->getCategoria_id() === $categoriaId) {
                $mueblesCategoria[] = $mueble;
            }
        }
        return $mueblesCategoria;
    }

    public static function filtrarPorRangoPrecio(array $muebles, float $min, float $max): array {
        $mueblesPrecio = [];

        foreach ($muebles as $mueble) {
            if ($mueble->getPrecio() >= $min && $mueble->getPrecio() <= $max) {
                $mueblesPrecio[] = $mueble;
            }
        }
        return $mueblesPrecio;
    }

    public static function filtrarPorColor(array $muebles, string $colorBuscado): array {
        $mueblesColor = [];
        $colorBuscado = strtolower($colorBuscado);

        foreach ($muebles as $mueble) {
            if (strtolower($mueble->getColor_principal()) === $colorBuscado) {
                $mueblesColor[] = $mueble;
            }
        }
        return $mueblesColor;
    }

    public static function filtrarPorNombre(array $muebles, string $textoBuscado): array {
        $mueblesNombre = [];
        $textoBuscado = strtolower($textoBuscado);

        foreach ($muebles as $mueble) {
            if (strpos(strtolower($mueble->getNombre()), $textoBuscado) !== false) {
                $mueblesNombre[] = $mueble;
            }
        }
        return $mueblesNombre;
    }

    public static function filtrarPorDescripcion(array $muebles, string $textoBuscado): array {
        $mueblesDescripcion = [];
        $textoBuscado = strtolower($textoBuscado);

        foreach ($muebles as $mueble) {
            if (strpos(strtolower($mueble->getDescripcion()), $textoBuscado) !== false) {
                $mueblesDescripcion[] = $mueble;
            }
        }
        return $mueblesDescripcion;
    }

    public static function ordenarPorPrecio(array $muebles, string $dir = 'asc'): array {
        $mueblesOrdenados = $muebles;

        usort($mueblesOrdenados, function($a, $b) use ($dir) {
            if ($dir === 'asc') {
                if ($a->getPrecio() == $b->getPrecio()) {
                    return 0;
                } elseif ($a->getPrecio() < $b->getPrecio()) {
                    return -1;
                } else {
                    return 1;
                }
            } else { // desc
                if ($a->getPrecio() == $b->getPrecio()) {
                    return 0;
                } elseif ($a->getPrecio() > $b->getPrecio()) {
                    return -1;
                } else {
                    return 1;
                }
            }
        });

        return $mueblesOrdenados;
    }

    public static function ordenarPorNombre(array $muebles, string $dir = 'asc'): array {
        $mueblesOrdenados = $muebles;

        usort($mueblesOrdenados, function($a, $b) use ($dir) {
            if ($dir === 'asc') {
                if ($a->getNombre() == $b->getNombre()) {
                    return 0;
                } elseif ($a->getNombre() < $b->getNombre()) {
                    return -1;
                } else {
                    return 1;
                }
            } else {
                if ($a->getNombre() == $b->getNombre()) {
                    return 0;
                } elseif ($a->getNombre() > $b->getNombre()) {
                    return -1;
                } else {
                    return 1;
                }
            }
        });

        return $mueblesOrdenados;
    }

    public static function ordenarPorNovedad(array $muebles, string $dir = 'asc'): array {
    $mueblesOrdenados = $muebles;

    usort($mueblesOrdenados, function($a, $b) use ($dir) {
        // Compara por destacado primero
        if ($a->getDestacado() !== $b->getDestacado()) {
            if ($dir === 'asc') {
                return $a->getDestacado() ? -1 : 1;
            } else {
                return $a->getDestacado() ? 1 : -1;
            }
        }

        // Si tienen el mismo destacado, compara por ID
        if ($a->getId() == $b->getId()) {
            return 0;
        }

        if ($dir === 'asc') {
            return ($a->getId() < $b->getId()) ? -1 : 1;
        } else {
            return ($a->getId() > $b->getId()) ? -1 : 1;
        }
    });

    return $mueblesOrdenados;
}


    public static function paginar(array $muebles, int $pagina, int $porPagina = 6): array
    {
        $offset = ($pagina - 1) * $porPagina;
        return array_slice($muebles, $offset, $porPagina);
    }
}

