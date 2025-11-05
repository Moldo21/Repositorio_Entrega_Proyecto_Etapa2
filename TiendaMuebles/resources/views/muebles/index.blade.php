@extends('cabecera')

@section('contenido')
<h2 class="mb-4">Catálogo de Muebles</h2>

{{-- Mensajes --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

{{-- Formulario de filtros --}}
<form method="GET" action="{{ route('muebles.index') }}" class="row g-3 mb-4">
    <input type="hidden" name="sesionId" value="{{ $sesionId }}">

    <div class="col-md-2">
        <label for="categoria" class="form-label">Categoría</label>
        <select name="categoria" id="categoria" class="form-select">
            <option value="">Todas</option>
            <option value="1" {{ request('categoria') == 1 ? 'selected' : '' }}>Sillas</option>
            <option value="2" {{ request('categoria') == 2 ? 'selected' : '' }}>Mesas</option>
            <option value="3" {{ request('categoria') == 3 ? 'selected' : '' }}>Camas</option>
            <option value="4" {{ request('categoria') == 4 ? 'selected' : '' }}>Estanterías</option>
        </select>
    </div>

    <div class="col-md-2">
        <label for="min" class="form-label">Precio min</label>
        <input type="number" name="min" id="min" class="form-control" step="0.01" value="{{ request('min') }}" placeholder="0">
    </div>

    <div class="col-md-2">
        <label for="max" class="form-label">Precio max</label>
        <input type="number" name="max" id="max" class="form-control" step="0.01" value="{{ request('max') }}" placeholder="1000">
    </div>

    <div class="col-md-2">
        <label for="color" class="form-label">Color</label>
        <input type="text" name="color" id="color" class="form-control" value="{{ request('color') }}" placeholder="Ej. Gris">
    </div>

    <div class="col-md-2">
        <label for="busqueda" class="form-label">Buscar</label>
        <input type="text" name="busqueda" id="busqueda" class="form-control" value="{{ request('busqueda') }}" placeholder="Nombre o descripción">
    </div>

    <div class="col-12 mt-2">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

{{-- Ordenamiento --}}
<div class="mb-3">
    <strong>Ordenar por:</strong>
    @php
        $dir = request('dir', 'asc') === 'asc' ? 'desc' : 'asc';
    @endphp
    <a href="{{ route('muebles.index', array_merge(request()->query(), ['orden' => 'precio', 'dir' => $dir])) }}">
        Precio @if (request('orden') === 'precio') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
    </a> |
    <a href="{{ route('muebles.index', array_merge(request()->query(), ['orden' => 'nombre', 'dir' => $dir])) }}">
        Nombre @if (request('orden') === 'nombre') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
    </a> |
    <a href="{{ route('muebles.index', array_merge(request()->query(), ['orden' => 'novedad', 'dir' => $dir])) }}">
        Novedad @if (request('orden') === 'novedad') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
    </a>
</div>

{{-- Listado de muebles --}}
@php
    $moneda = Cookie::get('moneda', 'EUR');
    $simbolo = $moneda === 'USD' ? '$' : '€';
@endphp
<div class="row">
    @forelse ($muebles as $m)
        <div class="col-md-3 mb-3">
            <div class="card">
                <form method="POST" action="{{ route('carrito.add', ['mueble' => $m->getId(), 'sesionId' => $sesionId]) }}">
                    @csrf
                    <img src="{{ asset('imagenes/' . $m->getImagenes()[0]) }}" class="card-img-top" alt="{{ $m->getNombre() }}">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $m->getNombre() }}</h5>
                        <p><strong>{{ $simbolo }}{{ number_format($m->getPrecio(), 2) }}</strong></p>
                        <button class="btn btn-primary" type="submit">Agregar al carrito</button>
                        <a href="{{ route('muebles.show', ['id' => $m->getId(), 'sesionId' => $sesionId]) }}" class="btn btn-outline-secondary mt-2">Ver detalles</a>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <p>No se encontraron muebles que coincidan con los filtros.</p>
    @endforelse
</div>

{{-- Paginación --}}
<div class="mt-4">
    @php
        $porPagina = Cookie::get('paginacion', 6);
        $totalPaginas = ceil($total / $porPagina);
    @endphp

    @if ($totalPaginas > 1)
        <nav>
            <ul class="pagination justify-content-center">
                @for ($i = 1; $i <= $totalPaginas; $i++)
                    <li class="page-item {{ $i == $pagina ? 'active' : '' }}">
                        <a class="page-link" href="{{ route('muebles.index', array_merge(request()->query(), ['pagina' => $i])) }}">{{ $i }}</a>
                    </li>
                @endfor
            </ul>
        </nav>
    @endif
</div>
@endsection
