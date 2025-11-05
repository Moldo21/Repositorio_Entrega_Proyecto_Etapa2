<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body {
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffffcc; /* Caja blanca semi-transparente */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            width: 320px;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 20px;
            color: #1e3a8a;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #bbb;
            border-radius: 6px;
            font-size: 1rem;
        }

        .checkbox {
            text-align: left;
            margin-bottom: 15px;
        }

        .checkbox label {
            font-size: 14px;
            color: #333;
            margin-left: 5px;
        }

        .login-container button {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #059669;
        }

        .alert-error {
            background-color: #ffe5e5;
            border-left: 5px solid #ef4444;
            color: #b91c1c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: left;
        }

        .alert-success {
            background-color: #d1fae5;
            border-left: 5px solid #10b981;
            color: #065f46;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Iniciar sesión</h1>

        {{-- Mensajes de éxito --}}
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensajes de error --}}
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required>
            </div>
            <div>
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="checkbox">
                <input type="checkbox" name="recuerdame" value="1" id="recuerdame">
                <label for="recuerdame">Recordar contraseña</label>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>
