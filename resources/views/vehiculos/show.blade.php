@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        {{-- Imágenes y detalle --}}
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">

 {{-- Imágenes del vehículo --}}
@if($vehiculo->imagenes->count() > 0)
    <div id="carouselImagenes" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($vehiculo->imagenes as $index => $imagen)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    {{-- Contenedor de tamaño fijo --}}
                    <div style="width: 100%; height: 350px; background-color: #f8f9fa;
                                display: flex; align-items: center; justify-content: center;
                                overflow: hidden;">
                        <img src="{{ $imagen->url_imagen }}"
                             alt="{{ $imagen->descripcion }}"
                             style="max-width: 100%; max-height: 100%;
                                    object-fit: contain;">
                    </div>
                </div>
            @endforeach
        </div>

        @if($vehiculo->imagenes->count() > 1)
            <button class="carousel-control-prev" type="button"
                    data-bs-target="#carouselImagenes" data-bs-slide="prev"
                    style="width: 40px; height: 40px; background: rgba(0,0,0,0.5);
                           border-radius: 50%; top: 50%; transform: translateY(-50%);
                           left: 10px; position: absolute;">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button"
                    data-bs-target="#carouselImagenes" data-bs-slide="next"
                    style="width: 40px; height: 40px; background: rgba(0,0,0,0.5);
                           border-radius: 50%; top: 50%; transform: translateY(-50%);
                           right: 10px; position: absolute;">
                <span class="carousel-control-next-icon"></span>
            </button>
        @endif
    </div>
@else
    <div class="bg-light text-center py-5 mb-4 rounded">
        <span class="text-muted">Sin imágenes disponibles</span>
    </div>
@endif

                    <h4>{{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio }})</h4>
                    <p class="text-muted">{{ $vehiculo->descripcion ?? 'Sin descripción.' }}</p>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Vendedor:</strong> {{ $vehiculo->vendedor->nombre ?? '—' }}</p>
                            <p><strong>Publicado:</strong> {{ \Carbon\Carbon::parse($vehiculo->fecha_publicacion)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ubicación:</strong> {{ $vehiculo->ubicacion->ciudad ?? '—' }}, {{ $vehiculo->ubicacion->pais ?? '' }}</p>
                            <p><strong>Estado:</strong>
                                <span class="badge {{ $vehiculo->estado === 'disponible' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($vehiculo->estado) }}
                                </span>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Panel de acciones --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <h3 class="text-success mb-3">${{ number_format($vehiculo->precio, 2) }}</h3>

                   {{-- Botón comprar - lo habilita Kim cuando cree CompraController --}}
{{--
@if($vehiculo->estado === 'disponible' && Auth::user()->tipo_usuario === 'cliente')
    <a href="{{ route('compras.create', ['id_vehiculo' => $vehiculo->id_vehiculo]) }}"
       class="btn btn-success w-100 mb-2">
        Comprar ahora
    </a>
@elseif($vehiculo->estado === 'vendido')
    <button class="btn btn-secondary w-100 mb-2" disabled>
        Vehículo vendido
    </button>
@endif
--}}

                    {{-- Botón favorito - solo para clientes --}}
                    @if(Auth::user()->tipo_usuario === 'cliente'&& $vehiculo->estado === 'disponible')
                        <form method="POST" action="{{ route('favoritos.store') }}">
                            @csrf
                            <input type="hidden" name="id_vehiculo" value="{{ $vehiculo->id_vehiculo }}">
                            <button type="submit" class="btn btn-outline-warning w-100 mb-2">
                                ♥ Agregar a favoritos
                            </button>
                        </form>
                    @endif

                   {{-- Botones editar/eliminar para dueño o admin --}}
@if(Auth::user()->tipo_usuario === 'admin' || Auth::user()->id_usuario === $vehiculo->id_vendedor)
    <hr>
    <a href="{{ route('vehiculos.edit', $vehiculo->id_vehiculo) }}"
       class="btn btn-outline-secondary w-100 mb-2">
        Editar vehículo
    </a>

    {{-- Botón agregar imagen --}}
    <a href="{{ route('imagenes-vehiculo.create', ['id_vehiculo' => $vehiculo->id_vehiculo]) }}"
       class="btn btn-outline-primary w-100 mb-2">
        + Agregar imagen
    </a>
@endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection