@extends('cabecera')

@section('contenido')
<div class="container mt-4">
    <h2 class="text-center mb-4">Elige una categoría</h2>

    @if (!isset($categorias) || empty($categorias))
        <div class="text-center mt-4">
            <p>No hay categorías disponibles.</p>
        </div>
    @else
        <div class="row justify-content-center">
            @foreach ($categorias as $categoria)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $categoria->getNombre() }}</h5>
                            <p class="card-text text-muted">{{ $categoria->getDescripcion() }}</p>
                            <a href="{{ route('categorias.show', [
                                    'id' => $categoria->getId(),
                                    'sesionId' => $sesionId ?? ''
                                ]) }}"
                               class="btn btn-primary w-100"
                               title="Ver muebles de la categoría {{ $categoria->getNombre() }}">
                                Ver muebles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <hr>

    <div class="text-center mt-3">
        <a href="{{ route('muebles.index', ['sesionId' => $sesionId ?? '']) }}" class="btn btn-secondary">
            Ver todos los muebles
        </a>
    </div>
</div>
@endsection
