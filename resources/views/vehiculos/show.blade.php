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
                                        <div style="width: 100%; height: 350px; background-color: #f8f9fa;
                                                    display: flex; align-items: center; justify-content: center;
                                                    overflow: hidden;">
                                            <img src="{{ $imagen->url_imagen }}"
                                                 alt="{{ $imagen->descripcion }}"
                                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
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

            {{-- Sección de reseñas --}}
            @if($vehiculo->resenas->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Reseñas ({{ $vehiculo->resenas->count() }})</h6>
                        @php
                            $promedio = $vehiculo->resenas->avg('calificacion');
                        @endphp
                        <span>
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= round($promedio) ? '#ffc107' : '#6c757d' }}">★</span>
                            @endfor
                            <span class="text-white-50 small ms-1">{{ number_format($promedio, 1) }}/5</span>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @foreach($vehiculo->resenas as $resena)
                            <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $resena->usuario->nombre ?? 'Usuario' }}</strong>
                                        <span class="text-muted small ms-2">
                                            {{ \Carbon\Carbon::parse($resena->fecha)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <span style="color: {{ $i <= $resena->calificacion ? '#ffc107' : '#dee2e6' }}">★</span>
                                            @endfor
                                        </div>

                                        {{-- Botones editar/eliminar solo para el autor --}}
                                        @if(Auth::user()->id_usuario === $resena->id_usuario)
                                            <a href="{{ route('resenas.edit', $resena->id_resena) }}"
                                               class="btn btn-sm btn-outline-secondary">Editar</a>

                                            <form method="POST"
                                                  action="{{ route('resenas.destroy', $resena->id_resena) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Seguro que querés eliminar esta reseña?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                @if($resena->comentario)
                                    <p class="text-muted mb-0 mt-1 small">{{ $resena->comentario }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Panel de acciones --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <h3 class="text-success mb-3">${{ number_format($vehiculo->precio, 2) }}</h3>

                    {{-- Botón favorito --}}
                    @if(Auth::user()->tipo_usuario === 'cliente' && $vehiculo->estado === 'disponible')
                        <form method="POST" action="{{ route('favoritos.store') }}">
                            @csrf
                            <input type="hidden" name="id_vehiculo" value="{{ $vehiculo->id_vehiculo }}">
                            <button type="submit" class="btn btn-outline-warning w-100 mb-2">
                                ♥ Agregar a favoritos
                            </button>
                        </form>
                    @endif

                    {{-- Botón dejar reseña --}}
                    @auth
                        @php
                            $comproPagado = $vehiculo->compras
                                ->where('id_usuario', Auth::user()->id_usuario)
                                ->where('estado', 'pagado')
                                ->isNotEmpty();

                            $yaReseno = $vehiculo->resenas
                                ->where('id_usuario', Auth::user()->id_usuario)
                                ->isNotEmpty();
                        @endphp

                        @if($comproPagado && !$yaReseno)
                            <hr>
                            <a href="{{ route('resenas.create', ['id_vehiculo' => $vehiculo->id_vehiculo]) }}"
                               class="btn btn-outline-warning w-100 mb-2">
                                ★ Dejar reseña
                            </a>
                        @endif
                    @endauth

                    {{-- Botones editar/eliminar para dueño o admin --}}
                    @if(Auth::user()->tipo_usuario === 'admin' || Auth::user()->id_usuario === $vehiculo->id_vendedor)
                        <hr>
                        <a href="{{ route('vehiculos.edit', $vehiculo->id_vehiculo) }}"
                           class="btn btn-outline-secondary w-100 mb-2">
                            Editar vehículo
                        </a>
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