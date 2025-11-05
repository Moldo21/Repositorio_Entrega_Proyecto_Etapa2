<?php

namespace App\Models;

use JsonSerializable;

class Categoria implements JsonSerializable
{
    private int $id;
    private string $nombre;
    private string $descripcion;

    public function __construct(int $id, string $nombre, string $descripcion){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function jsonSerialize(): array{
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ];
    }

    // ====== Getters públicos ======
    public function getId(): int{
        return $this->id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function getDescripcion(): string{
        return $this->descripcion;
    }

    // ====== Método de datos simulados ======
    public static function datosCategorias(): array{
        return [
            new Categoria(1, 'Sillas','Comodidad y estilo para tu hogar'),
            new Categoria(2, 'Mesas','Funcionales y elegantes para cualquier espacio'),
            new Categoria(3, 'Camas','Descanso y diseño en un solo lugar'),
            new Categoria(4, 'Estanterías','Organiza con estilo y practicidad'),
        ];
    }

    public static function buscarPorId(int $id): ?Categoria{
        foreach (Categoria::datosCategorias() as $categoria) {
            if ($categoria->getId() === $id) {
                return $categoria;
            }
        }
        return null;
    }
}
