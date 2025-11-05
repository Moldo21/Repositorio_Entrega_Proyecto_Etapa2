@extends('cabecera')

@section('contenido')
<div class="container mt-4">
    <h2 class="mb-4">Preferencias</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('preferencias.update', ['sesionId' => $sesionId]) }}" method="POST" class="mb-4">
        @csrf
        <input type="hidden" name="sesionId" value="{{ $sesionId }}">

        <div class="mb-3">
            <label for="tema" class="form-label">Tema de la página:</label>
            <select name="tema" id="tema" class="form-select">
                <option value="claro" {{ $tema == 'claro' ? 'selected' : '' }}>Claro</option>
                <option value="oscuro" {{ $tema == 'oscuro' ? 'selected' : '' }}>Oscuro</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="moneda" class="form-label">Moneda:</label>
            <select name="moneda" id="moneda" class="form-select">
                <option value="EUR" {{ $moneda == 'EUR' ? 'selected' : '' }}>EUR</option>
                <option value="USD" {{ $moneda == 'USD' ? 'selected' : '' }}>USD</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="paginacion" class="form-label">Tamaño de paginación:</label>
            <select name="paginacion" id="paginacion" class="form-select">
                <option value="6" {{ $paginacion == 6 ? 'selected' : '' }}>6</option>
                <option value="12" {{ $paginacion == 12 ? 'selected' : '' }}>12</option>
                <option value="24" {{ $paginacion == 24 ? 'selected' : '' }}>24</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar preferencias</button>
    </form>

    <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">
        Volver a la página principal
    </a>
</div>
@endsection
