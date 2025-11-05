<?php

namespace App\Http\Controllers;

use App\Models\Mueble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MuebleController extends Controller
{
    public function index(Request $request)
    {
        $muebles = Mueble::datosMuebles();

        // Filtrar por categoría
        $categoriaId = $request->query('categoria');
        if ($categoriaId !== null && (int) $categoriaId > 0) {
            $muebles = Mueble::filtrarPorCategoria($muebles, (int) $categoriaId);
            Cookie::queue('categoria_id', $categoriaId, 60 * 24 * 30);
        }

        // Filtros adicionales
        $minPrecio = $request->query('min');
        $maxPrecio = $request->query('max');
        $color = $request->query('color');
        $busqueda = $request->query('busqueda');

        // Validación del rango de precios
        if ($minPrecio && $maxPrecio) {
            if ($minPrecio > $maxPrecio) {
                return redirect()->route('muebles.index', $request->except(['min', 'max']))
                    ->withErrors(['rango_precio' => 'El precio mínimo no puede ser mayor que el precio máximo.']);
            }
            $muebles = Mueble::filtrarPorRangoPrecio($muebles, (float) $minPrecio, (float) $maxPrecio);
        }

        if ($color) {
            $muebles = Mueble::filtrarPorColor($muebles, strtolower($color));
        }

        if ($busqueda) {
            $busqueda = strtolower($busqueda);
            $mueblesNombre = Mueble::filtrarPorNombre($muebles, $busqueda);
            $mueblesDescripcion = Mueble::filtrarPorDescripcion($muebles, $busqueda);
            $muebles = array_unique(array_merge($mueblesNombre, $mueblesDescripcion), SORT_REGULAR);
        }

        // Ordenamiento
        $orden = $request->query('orden');
        $direccion = $request->query('dir', 'asc');
        if ($orden === 'precio') {
            $muebles = Mueble::ordenarPorPrecio($muebles, $direccion);
        } elseif ($orden === 'nombre') {
            $muebles = Mueble::ordenarPorNombre($muebles, $direccion);
        } elseif ($orden === 'novedad') {
            $muebles = Mueble::ordenarPorNovedad($muebles, $direccion);
        }

        // Paginación
        $porPagina = (int) $request->cookie('paginacion', 6);
        $pagina = (int) $request->query('pagina', 1);
        if ($request->has('paginacion')) {
            $porPagina = (int) $request->input('paginacion');
            Cookie::queue('paginacion', $porPagina, 60 * 24 * 30);
        }
        $mueblesPaginados = Mueble::paginar($muebles, $pagina, $porPagina);

        // Usuario desde sesión
        $sesionId = $request->query('sesionId');
        $usuario = session()->get('usuarios')[$sesionId] ?? null;
        $usuario = $usuario ? json_decode($usuario) : null;

        return view('muebles.index', [
            'muebles' => $mueblesPaginados,
            'pagina' => $pagina,
            'total' => count($muebles),
            'sesionId' => $sesionId,
            'usuario' => $usuario,
        ]);
    }

    public function guardarPreferencia(Request $request)
    {
        $porPagina = $request->input('por_pagina', 6);
        Cookie::queue('muebles_por_pagina', $porPagina, 60 * 24 * 30);

        return redirect()->route('muebles.index');
    }

    public function show(int $id, Request $request)
    {
        $mueble = Mueble::buscarPorId($id);

        if (! $mueble) {
            abort(404, 'Mueble no encontrado');
        }

        Cookie::queue('mueble_'.$id, json_encode($mueble), 60 * 24);

        $sesionId = $request->query('sesionId');
        $usuario = session()->get('usuarios')[$sesionId] ?? null;
        $usuario = $usuario ? json_decode($usuario) : null;

        return view('muebles.show', [
            'mueble' => $mueble,
            'sesionId' => $sesionId,
            'usuario' => $usuario,
        ]);
    }
}
