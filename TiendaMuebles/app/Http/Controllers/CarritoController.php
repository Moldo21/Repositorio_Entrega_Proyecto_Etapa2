<?php

namespace App\Http\Controllers;

use App\Models\Mueble;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CarritoController extends Controller
{
    public function show(Request $request)
    {
        $sesionId = $request->query('sesionId');
        $usuario = Usuario::usuarioActivoSesion($sesionId);

        if (!$usuario) {
            return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debes iniciar sesión']);
        }

        $cookieCarrito = $request->cookie('carrito_' . $usuario->id);
        $carrito = $cookieCarrito ? json_decode($cookieCarrito, true) : [];
        $total = 0;

        foreach ($carrito as $id => $item) {
            if (!isset($item['stock'])) {
                $mueble = Mueble::buscarPorId($id);
                $carrito[$id]['stock'] = $mueble ? $mueble->getStock() : 0;
            }
            $total += $carrito[$id]['precio'] * $carrito[$id]['cantidad'];
        }

        session(['carrito_' . $usuario->id => $carrito]);

        $response = response()->view('carrito.index', compact('carrito', 'total', 'usuario', 'sesionId'));
        return $response->withCookie(cookie('carrito_' . $usuario->id, json_encode($carrito), 60 * 24 * 7));
    }

    public function add(Request $request, int $mueble)
    {
        $sesionId = $request->query('sesionId');
        $usuario = Usuario::usuarioActivoSesion($sesionId);
        if (!$usuario) {
            return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debes iniciar sesión']);
        }

        $m = Mueble::buscarPorId($mueble);
        if (!$m) {
            return redirect()->route('muebles.index', ['sesionId' => $sesionId])
                             ->withErrors('Mueble no encontrado');
        }

        $cookieCarrito = $request->cookie('carrito_' . $usuario->id);
        $carrito = $cookieCarrito ? json_decode($cookieCarrito, true) : [];
        $cantidadActual = $carrito[$mueble]['cantidad'] ?? 0;

        if ($cantidadActual + 1 > $m->getStock()) {
            return redirect()->back()
                             ->withErrors("No se pueden añadir más unidades de '{$m->getNombre()}'. Stock disponible: {$m->getStock()}");
        }

        $carrito[$mueble] = [
            'nombre' => $m->getNombre(),
            'precio' => $m->getPrecio(),
            'cantidad' => $cantidadActual + 1,
            'stock' => $m->getStock()
        ];

        session(['carrito_' . $usuario->id => $carrito]);

        return redirect()->route('carrito.show', ['sesionId' => $sesionId])
                         ->with('success', "Se añadió 1 unidad de '{$m->getNombre()}' al carrito.")
                         ->withCookie(cookie('carrito_' . $usuario->id, json_encode($carrito), 60 * 24 * 7));
    }

    public function aumentar(Request $request, int $id)
    {
        $sesionId = $request->query('sesionId');
        $usuario = Usuario::usuarioActivoSesion($sesionId);
        if (!$usuario) return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debes iniciar sesión.']);

        $cookieCarrito = $request->cookie('carrito_' . $usuario->id);
        $carrito = $cookieCarrito ? json_decode($cookieCarrito, true) : [];

        if (!isset($carrito[$id])) return redirect()->back()->withErrors('Mueble no encontrado en el carrito.');

        $mueble = Mueble::buscarPorId($id);
        if (!$mueble) return redirect()->back()->withErrors('Mueble no encontrado.');

        if (!isset($carrito[$id]['stock'])) $carrito[$id]['stock'] = $mueble->getStock();

        if ($carrito[$id]['cantidad'] + 1 <= $carrito[$id]['stock']) $carrito[$id]['cantidad']++;

        session(['carrito_' . $usuario->id => $carrito]);

        return redirect()->back()->withCookie(cookie('carrito_' . $usuario->id, json_encode($carrito), 60 * 24 * 7));
    }

    public function disminuir(Request $request, int $id)
    {
        $sesionId = $request->query('sesionId');
        $usuario = Usuario::usuarioActivoSesion($sesionId);
        if (!$usuario) return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debes iniciar sesión.']);

        $cookieCarrito = $request->cookie('carrito_' . $usuario->id);
        $carrito = $cookieCarrito ? json_decode($cookieCarrito, true) : [];

        if (!isset($carrito[$id])) return redirect()->back()->withErrors('Mueble no encontrado en el carrito.');

        if ($carrito[$id]['cantidad'] > 1) {
            $carrito[$id]['cantidad']--;
        } else {
            unset($carrito[$id]);
        }

        session(['carrito_' . $usuario->id => $carrito]);

        return redirect()->back()->withCookie(cookie('carrito_' . $usuario->id, json_encode($carrito), 60 * 24 * 7));
    }

    public function remove(Request $request, int $mueble)
    {
        $sesionId = $request->query('sesionId');
        $usuario = Usuario::usuarioActivoSesion($sesionId);
        if (!$usuario) return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debes iniciar sesión.']);

        $cookieCarrito = $request->cookie('carrito_' . $usuario->id);
        $carrito = $cookieCarrito ? json_decode($cookieCarrito, true) : [];

    unset($carrito[$mueble]);

    session(['carrito_' . $usuario->id => $carrito]);

    return redirect()->route('carrito.show', ['sesionId' => $sesionId])
             ->with('success', 'Mueble eliminado del carrito')
             ->withCookie(cookie('carrito_' . $usuario->id, json_encode($carrito), 60 * 24 * 7));
    }

    public function clear(Request $request)
    {
        $sesionId = $request->query('sesionId');
        $usuario = Usuario::usuarioActivoSesion($sesionId);
        if (!$usuario) return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debes iniciar sesión.']);

        session(['carrito_' . $usuario->id => []]);

        $response = redirect()->route('carrito.show', ['sesionId' => $sesionId])
                     ->with('success', 'Carrito vaciado');

        return $response->withCookie(Cookie::forget('carrito_' . $usuario->id));
    }
}
