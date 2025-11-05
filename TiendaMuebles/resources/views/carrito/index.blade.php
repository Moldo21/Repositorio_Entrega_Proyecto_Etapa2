@extends('cabecera')

@section('contenido')
    <h2>Tu carrito</h2>

    @php
        $moneda = Cookie::get('moneda', 'EUR');
        $simbolo = $moneda === 'USD' ? '$' : '€';
    @endphp

    {{-- Mensajes de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Contenido del carrito --}}
    @if (empty($carrito))
        <p>No tienes muebles en el carrito.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carrito as $id => $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $simbolo }}{{ number_format($item['precio'], 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <form method="POST"
                                    action="{{ route('carrito.disminuir', ['id' => $id, 'sesionId' => $sesionId]) }}"
                                    class="me-1">
                                    @csrf
                                    <button class="btn btn-sm btn-secondary">-</button>
                                </form>
                                <span class="px-2">{{ $item['cantidad'] }}</span>
                                <form method="POST"
                                    action="{{ route('carrito.aumentar', ['id' => $id, 'sesionId' => $sesionId]) }}"
                                    class="ms-1">
                                    @csrf
                                    <button class="btn btn-sm btn-primary"
                                        @if ($item['cantidad'] >= $item['stock']) disabled @endif>+</button>
                                </form>
                            </div>
                        </td>
                        <td>{{ $simbolo }}{{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                        <td>
                            <form method="POST"
                                action="{{ route('carrito.remove', ['mueble' => $id, 'sesionId' => $sesionId]) }}">
                                @csrf
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Total: {{ $simbolo }}{{ number_format($total, 2) }}</h4>

        {{-- Botones de acciones --}}
        <div class="mt-3 d-flex gap-2">
            <form method="POST" action="{{ route('carrito.clear', ['sesionId' => $sesionId]) }}">
                @csrf
                <button class="btn btn-warning">Vaciar carrito</button>
            </form>
            <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Seguir comprando</a>
        </div>
    @endif

    {{-- Botón siempre visible para volver al catálogo --}}
    <div class="mt-3">
        <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-primary">
            Volver al catálogo
        </a>
    </div>
@endsection
