<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PreferenciasController extends Controller
{
    public function edit(Request $request)
    {
        $tema = $request->cookie('tema', 'claro');
        $moneda = $request->cookie('moneda', 'EUR');
        $paginacion = $request->cookie('paginacion', 6);
        $sesionId = $request->query('sesionId');

        return view('preferencias.edit', compact('tema', 'moneda', 'paginacion', 'sesionId'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tema' => 'required|in:claro,oscuro',
            'moneda' => 'required|string|max:3',
            'paginacion' => 'required|integer|in:6,12,24',
        ]);

        $cookieTema = cookie('tema', $request->tema, 60 * 24 * 30);
        $cookieMoneda = cookie('moneda', $request->moneda, 60 * 24 * 30);
        $cookiePaginacion = cookie('paginacion', $request->paginacion, 60 * 24 * 30);

        return redirect()
            ->route('preferencias.edit', ['sesionId' => $request->input('sesionId')])
            ->with('success', 'Preferencias guardadas correctamente.')
            ->cookie($cookieTema)
            ->cookie($cookieMoneda)
            ->cookie($cookiePaginacion);
    }
}
