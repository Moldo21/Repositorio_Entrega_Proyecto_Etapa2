<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Categoria;

class CategoriasController extends Controller {

    public function index(Request $request)
    {
        if (!session('autorizacion_usuario')) {
            return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debe iniciar sesión']);
        }
        $sesionId = $request->query('sesionId');
        $usuarios = session('usuarios', []);
        $usuario = $usuarios[$sesionId] ?? null;
        $usuario = $usuario ? json_decode($usuario) : null;

        $categorias = Categoria::datosCategorias();

        return view('categorias.index', [
            'categorias' => $categorias,
            'sesionId' => $sesionId,
            'usuario' => $usuario
        ]);
    }

    public function show(int $id, Request $request) {
        if (!session('autorizacion_usuario')) {
            return redirect()->route('login')->withErrors(['errorCredenciales' => 'Debe iniciar sesión']);
        }
        $sesionId = $request->query('sesionId');
        $usuarios = session('usuarios', []);
        $usuario = $usuarios[$sesionId] ?? null;
        $usuario = $usuario ? json_decode($usuario) : null;

        $categoria = Categoria::buscarPorId($id);

        if (!$categoria) {
            abort(404, 'Categoría no encontrada');
        }

        Cookie::queue('categoria_id', $id, 60 * 24 * 30);

        return redirect()->route('muebles.index', [
            'categoria' => $id,
            'sesionId' => $sesionId
        ]);
    }
}
