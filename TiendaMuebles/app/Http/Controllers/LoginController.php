<?php

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function show()
    {
        if (! Session::has('intentos_login')) {
            Session::put('intentos_login', []);
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $email = $datos['email'];

        $intentos = Session::get("intentos_login.$email", 0);

        if ($intentos >= 3) {
            return back()->withErrors([
                'errorBloqueo' => 'Demasiados intentos fallidos. Vuelve a intentarlo más tarde.',
            ]);
        }

        $usuario = Usuario::verificarCredenciales($datos['email'], $datos['password']);

        if (! $usuario) {
            $intentos++;
            Session::put("intentos_login.$email", $intentos);

            $restantes = max(3 - $intentos, 0);

            if ($intentos >= 3) {
                return back()->withErrors([
                    'errorBloqueo' => 'Credenciales incorrectas. Vuelve a intentarlo más tarde.',
                ]);
            }

            if ($restantes === 1) {
                return back()->withErrors([
                    'errorCredenciales' => "Credenciales incorrectas. Te queda $restantes intento.",
                ]);
            } else {
                return back()->withErrors([
                    'errorCredenciales' => "Credenciales incorrectas. Te quedan $restantes intentos.",
                ]);
            }
        }

        Session::forget("intentos_login.$email");

        $sesionId = Session::getId().'_'.$usuario->getId();

        $usuarios = Session::get('usuarios', []);

        $datosSesion = [
            'id' => $usuario->getId(),
            'email' => $usuario->getEmail(),
            'nombre' => $usuario->getNombre(),
            'rol' => $usuario->getRol()->value,
            'fecha_ingreso' => now()->toDateTimeString(),
            'sesionId' => $sesionId,
        ];

        $usuarios[$sesionId] = json_encode($datosSesion);
        Session::put('usuarios', $usuarios);
        Session::put('autorizacion_usuario', true);
        Session::regenerate();

        if ($request->has('recuerdame')) {
            config(['session.lifetime' => 43200]);
            config(['session.expire_on_close' => false]);
        }

        switch ($usuario->getRol()) {
            case RolUsuario::ADMIN:
                return redirect()->route('categorias.index', ['sesionId' => $sesionId]);
            case RolUsuario::GESTOR:
                return redirect()->route('categorias.index', ['sesionId' => $sesionId]);
            default:
                return redirect()->route('categorias.index', ['sesionId' => $sesionId]);
        }
    }

    public function logout(Request $request)
    {
        $sesionId = $request->input('sesionId') ?? $request->query('sesionId');
        $usuarios = Session::get('usuarios', []);

        if ($sesionId && isset($usuarios[$sesionId])) {
            unset($usuarios[$sesionId]);
            Session::put('usuarios', $usuarios);
        }

        Session::flush();
        Session::regenerate();

        return redirect()->route('login')->with('mensaje', 'Sesión cerrada correctamente.');
    }
}
