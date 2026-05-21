@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    
    {{-- Botón de retroceso sutil --}}
    <div class="mb-3">
        <a href="{{ route('vehiculos.indexCards') }}" class="text-muted text-decoration-none small text-uppercase tracking-wider">
            &larr; Volver al catálogo
        </a>
    </div>

    <div class="row g-4">

        {{-- LADO IZQUIERDO: Imágenes, Detalles y Reseñas --}}
        <div class="col-lg-8">
            
            {{-- Tarjeta Principal del Vehículo --}}
            <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden">
                
                {{-- Carrusel de Imágenes Premium --}}
                @if($vehiculo->imagenes->count() > 0)
                    <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner bg-dark">
                            @foreach($vehiculo->imagenes as $index => $imagen)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div style="width: 100%; height: 450px; background-color: var(--color-premium-dark);
                                                display: flex; align-items: center; justify-content: center;
                                                overflow: hidden;">
                                        <img src="{{ $imagen->url_imagen }}"
                                             alt="{{ $imagen->descripcion }}"
                                             style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($vehiculo->imagenes->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev"
                                    style="width: 50px; height: 50px; background: rgba(0,0,0,0.6); border-radius: 50%; top: 50%; transform: translateY(-50%); left: 20px; position: absolute;">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next"
                                    style="width: 50px; height: 50px; background: rgba(0,0,0,0.6); border-radius: 50%; top: 50%; transform: translateY(-50%); right: 20px; position: absolute;">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-dark text-center py-5 d-flex align-items-center justify-content-center" style="height: 300px;">
                        <span class="text-white-50 font-serif fs-4">Imagen No Disponible</span>
                    </div>
                @endif

                {{-- Cuerpo de Detalles --}}
                <div class="card-body p-4 p-md-5 bg-white">
                    <h2 class="font-serif fw-bold mb-1" style="color: var(--color-premium-dark);">
                        {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                    </h2>
                    <p class="text-muted fs-5 mb-4 border-bottom pb-3">Edición {{ $vehiculo->anio }}</p>

                    <h6 class="text-uppercase tracking-wider fw-bold text-muted mb-3">Descripción General</h6>
                    <p class="text-dark mb-4" style="line-height: 1.8;">
                        {{ $vehiculo->descripcion ?? 'Este vehículo no cuenta con una descripción detallada en este momento.' }}
                    </p>

                    <div class="row g-4 bg-light p-4 rounded-3 border">
                        <div class="col-sm-6">
                            <p class="mb-2"><strong class="text-muted text-uppercase small">Vendedor</strong><br>
                               <span class="fs-6">{{ $vehiculo->vendedor->nombre ?? '—' }}</span></p>
                            <p class="mb-0"><strong class="text-muted text-uppercase small">Publicado</strong><br>
                               <span class="fs-6">{{ \Carbon\Carbon::parse($vehiculo->fecha_publicacion)->format('d M, Y') }}</span></p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-2"><strong class="text-muted text-uppercase small">Ubicación</strong><br>
                               <span class="fs-6">{{ $vehiculo->ubicacion->ciudad ?? '—' }}, {{ $vehiculo->ubicacion->pais ?? '' }}</span></p>
                            <p class="mb-0"><strong class="text-muted text-uppercase small">Disponibilidad</strong><br>
                                @if($vehiculo->estado === 'disponible')
                                    <span class="badge bg-success px-3 py-2 text-uppercase">Disponible</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 text-uppercase">Vendido</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sección de Reseñas Premium --}}
            @if($vehiculo->resenas->count() > 0)
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-dark text-white p-4 d-flex justify-content-between align-items-center rounded-top-3">
                        <h5 class="mb-0 font-serif">Experiencia de Clientes ({{ $vehiculo->resenas->count() }})</h5>
                        @php $promedio = $vehiculo->resenas->avg('calificacion'); @endphp
                        <div class="d-flex align-items-center bg-black px-3 py-1 rounded-pill">
                            <span class="text-warning fs-5 me-2">★</span>
                            <span class="fw-bold">{{ number_format($promedio, 1) }}</span>
                            <span class="text-white-50 ms-1 small">/ 5</span>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        @foreach($vehiculo->resenas as $resena)
                            <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($resena->usuario->nombre ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $resena->usuario->nombre ?? 'Usuario' }}</h6>
                                            <span class="text-muted small">{{ \Carbon\Carbon::parse($resena->fecha)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span style="color: {{ $i <= $resena->calificacion ? 'var(--color-premium-gold)' : '#e9ecef' }}; font-size: 1.2rem;">★</span>
                                            @endfor
                                        </div>

                                        {{-- Botones editar/eliminar autor --}}
                                        @if(Auth::user()->id_usuario === $resena->id_usuario)
                                            <div class="mt-2">
                                                <a href="{{ route('resenas.edit', $resena->id_resena) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;">Editar</a>
                                                <form method="POST" action="{{ route('resenas.destroy', $resena->id_resena) }}" class="d-inline" onsubmit="return confirm('¿Seguro que querés eliminar esta reseña?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;">Eliminar</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($resena->comentario)
                                    <p class="text-dark mb-0 ms-5 fst-italic">"{{ $resena->comentario }}"</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- LADO DERECHO: Panel de Acciones (Sticky) --}}
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-3 sticky-top" style="top: 2rem;">
                <div class="card-body p-4 text-center">
                    
                    <p class="text-muted text-uppercase tracking-wider small mb-1">Precio de Venta</p>
                    <h2 class="mb-4 font-serif" style="color: var(--color-premium-dark);">
                        ${{ number_format($vehiculo->precio, 2) }}
                    </h2>

                    {{-- Botón favorito --}}
                    @if(Auth::user()->tipo_usuario === 'cliente' && $vehiculo->estado === 'disponible')
                        <form method="POST" action="{{ route('favoritos.store') }}">
                            @csrf
                            <input type="hidden" name="id_vehiculo" value="{{ $vehiculo->id_vehiculo }}">
                            <button type="submit" class="btn btn-outline-dark btn-lg w-100 mb-3 text-uppercase fw-bold" style="border-color: var(--color-premium-gold); color: var(--color-premium-gold);">
                                <span class="fs-5 me-2">♡</span> Añadir a Favoritos
                            </button>
                        </form>
                    @endif

                    {{-- Botón dejar reseña --}}
                    @auth
                        @php
                            $comproPagado = $vehiculo->compras->where('id_usuario', Auth::user()->id_usuario)->where('estado', 'pagado')->isNotEmpty();
                            $yaReseno = $vehiculo->resenas->where('id_usuario', Auth::user()->id_usuario)->isNotEmpty();
                        @endphp

                        @if($comproPagado && !$yaReseno)
                            <hr class="opacity-25 my-4">
                            <a href="{{ route('resenas.create', ['id_vehiculo' => $vehiculo->id_vehiculo]) }}" class="btn btn-premium w-100 mb-2">
                                ★ Calificar mi compra
                            </a>
                        @endif
                    @endauth

                    {{-- Botones de Administración / Propietario --}}
                    @if(Auth::user()->tipo_usuario === 'admin' || Auth::user()->id_usuario === $vehiculo->id_vendedor)
                        <div class="bg-light p-3 rounded-3 mt-4 border">
                            <h6 class="text-muted text-uppercase small mb-3">Panel de Gestión</h6>
                            <a href="{{ route('vehiculos.edit', $vehiculo->id_vehiculo) }}" class="btn btn-dark w-100 mb-2">
                                Editar Vehículo
                            </a>
                            <a href="{{ route('imagenes-vehiculo.create', ['id_vehiculo' => $vehiculo->id_vehiculo]) }}" class="btn btn-outline-secondary w-100">
                                + Agregar Imagen
                            </a>
                        </div>
                    @endif

                    {{-- Mensaje de Confianza --}}
                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="small text-muted mb-0">
                            <span class="text-success fs-5">✓</span> Compra segura a través de Auto506
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection