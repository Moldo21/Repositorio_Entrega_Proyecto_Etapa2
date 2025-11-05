<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda de Muebles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.claro {
            background-color: #fff;
            color: #000;
        }

        body.oscuro {
            background-color: #121212;
            color: #fff;
        }

        /* Cards consistentes con temas */
        .card {
            background-color: inherit; /* Hereda el color del body */
            color: inherit;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, color 0.3s;
        }

        /* Botones dentro de las cards */
        .card button, .card a.btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            text-decoration: none;
        }

        .card button:hover, .card a.btn:hover {
            background-color: #0056b3;
            color: #fff;
        }

        /* Navbar adaptativa al tema */
        body.claro .navbar {
            background-color: #343a40;
        }

        body.oscuro .navbar {
            background-color: #1f1f1f;
        }

        body.oscuro .navbar .nav-link,
        body.oscuro .navbar .btn-outline-light {
            color: #fff;
        }
    </style>
</head>

<body class="{{ Cookie::get('tema', 'claro') }}">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a href="{{ route('muebles.index', ['sesionId' => $sesionId ?? null]) }}" class="navbar-brand">
                Kctta
            </a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    @if (isset($usuario))
                        <li class="nav-item me-2">
                            <span class="nav-link disabled">
                                {{ $usuario->nombre ?? '' }} - {{ $usuario->rol ?? '' }}
                            </span>
                        </li>
                    @endif

                    @if (isset($sesionId))
                        <li class="nav-item me-2">
                            <a href="{{ route('carrito.show', ['sesionId' => $sesionId]) }}"
                               class="btn btn-sm btn-outline-light">Carrito</a>
                        </li>
                        <li class="nav-item me-2">
                            <a href="{{ route('preferencias.edit', ['sesionId' => $sesionId]) }}"
                               class="btn btn-sm btn-outline-light">Preferencias</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('login.logout') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="sesionId" value="{{ $sesionId }}">
                                <button class="btn btn-sm btn-outline-light" type="submit">Cerrar sesión</button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('contenido')
    </div>
</body>

</html>
