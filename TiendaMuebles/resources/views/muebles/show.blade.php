@extends('cabecera')

@section('contenido')
<div class="container mt-4">
    <div class="row">
        <!-- Carrusel de imágenes del mueble -->
        <div class="col-md-5">
            <div id="carouselMueble" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($mueble->getImagenes() as $index => $imagen)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('imagenes/' . $imagen) }}"
                                 class="d-block w-100 rounded shadow"
                                 alt="{{ $mueble->getNombre() }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselMueble" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselMueble" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>

        @php
            $moneda = Cookie::get('moneda', 'EUR');
            $simbolo = $moneda === 'USD' ? '$' : '€';
        @endphp

        <!-- Información del mueble -->
        <div class="col-md-7">
            <h2 class="{{ Cookie::get('tema', 'claro') == 'oscuro' ? 'text-white' : '' }}">
                {{ $mueble->getNombre() }}
            </h2>

            <p class="{{ Cookie::get('tema', 'claro') == 'oscuro' ? 'text-white' : '' }}">
                <strong>Categoría:</strong> {{ $mueble->getCategoriaNombre() }}
            </p>

            <p class="{{ Cookie::get('tema', 'claro') == 'oscuro' ? 'text-white' : '' }}">
                {{ $mueble->getDescripcion() }}
            </p>

            <ul class="list-group mb-3">
                <li class="list-group-item {{ Cookie::get('tema', 'claro') == 'oscuro' ? 'bg-dark text-white' : '' }}">
                    <strong>Precio:</strong> {{ $simbolo }}{{ number_format($mueble->getPrecio(), 2) }}
                </li>
                <li class="list-group-item {{ Cookie::get('tema', 'claro') == 'oscuro' ? 'bg-dark text-white' : '' }}">
                    <strong>Stock:</strong> {{ $mueble->getStock() }}
                </li>
                <li class="list-group-item {{ Cookie::get('tema', 'claro') == 'oscuro' ? 'bg-dark text-white' : '' }}">
                    <strong>Materiales:</strong> {{ $mueble->getMateriales() }}
                </li>
                <li class="list-group-item {{ Cookie::get('tema', 'claro') == 'oscuro' ? 'bg-dark text-white' : '' }}">
                    <strong>Dimensiones:</strong> {{ $mueble->getDimensiones() }}
                </li>
                <li class="list-group-item {{ Cookie::get('tema', 'claro') == 'oscuro' ? 'bg-dark text-white' : '' }}">
                    <strong>Color principal:</strong> {{ $mueble->getColor_principal() }}
                </li>
                <li class="list-group-item {{ Cookie::get('tema', 'claro') == 'oscuro' ? 'bg-dark text-white' : '' }}">
                    <strong>Destacado:</strong> {{ $mueble->getDestacado() ? 'Sí' : 'No' }}
                </li>
            </ul>

            <!-- Botones -->
            <form method="POST" action="{{ route('carrito.add', ['mueble' => $mueble->getId(), 'sesionId' => $sesionId]) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Agregar al carrito</button>
                <a href="{{ route('muebles.index', ['sesionId' => $sesionId]) }}" class="btn btn-secondary ms-2">
                    Volver al catálogo
                </a>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS para el carrusel -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
