@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- Cabecera del Módulo --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-serif mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Catálogo de Vehículos</h4>
        @if(Auth::user()->tipo_usuario === 'vendedor' || Auth::user()->tipo_usuario === 'admin')
            <a href="{{ route('vehiculos.create') }}" class="btn btn-premium px-4 fw-semibold text-uppercase small">
                + Publicar vehículo
            </a>
        @endif
    </div>

    {{-- Bloque de Filtros Rediseñado --}}
    <div class="card card-premium shadow-sm mb-5">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('vehiculos.indexCards') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="marca" class="form-control"
                               placeholder="Marca (Ej: BMW)" value="{{ request('marca') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="anio" class="form-control"
                               placeholder="Año" value="{{ request('anio') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="precio_max" class="form-control"
                               placeholder="Precio máximo" value="{{ request('precio_max') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="vendido"    {{ request('estado') == 'vendido'    ? 'selected' : '' }}>Vendido</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-premium w-100 fw-bold">Filtrar</button>
                        <a href="{{ route('vehiculos.indexCards') }}" class="btn btn-outline-secondary w-100">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Listado en formato Cards --}}
    <div class="row g-4">
        @forelse($vehiculos as $vehiculo)
            <div class="col-sm-6 col-lg-4">
                <div class="card vehicle-card shadow-sm h-100">
                    
                   
                    {{-- Imagen Principal extraída de la colección de imágenes --}}
<div class="vehicle-img-container">
    @if($vehiculo->imagenes && $vehiculo->imagenes->count() > 0)
        {{-- Tomamos solo la primera imagen de la relación para la portada --}}
        @php $primeraImagen = $vehiculo->imagenes->first(); @endphp
        
        <img src="{{ $primeraImagen->url_imagen }}" 
             class="vehicle-img" 
             alt="{{ $primeraImagen->descripcion ?? $vehiculo->marca }}">
    @else
        {{-- Imagen de marcador de posición elegante por si no tiene ninguna foto --}}
        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark">
            <span class="text-white-50 text-uppercase small tracking-wider">Sin Imagen Disponible</span>
        </div>
    @endif

    {{-- Etiqueta Flotante de Estado --}}
    @if($vehiculo->estado === 'disponible')
        <span class="badge-status-disponible">Disponible</span>
    @else
        <span class="badge-status-vendido">Vendido</span>
    @endif
</div>

                    {{-- Cuerpo de la Tarjeta --}}
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="vehicle-title mb-0 text-truncate me-2">
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                </h5>
                                <span class="badge bg-light text-dark border fw-bold">{{ $vehiculo->anio }}</span>
                            </div>

                            <div class="d-flex align-items-center text-muted small mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-geo-alt-fill me-1 text-premium-gold" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                </svg>
                                {{ $vehiculo->ubicacion->ciudad ?? 'Ubicación no especificada' }}
                            </div>

                            <div class="vehicle-card-price mb-3">
                                ${{ number_format($vehiculo->precio, 2) }}
                            </div>
                        </div>

                        {{-- Acciones del Vehículo --}}
                        <div class="mt-2">
                            <div class="d-grid mb-2">
                                <a href="{{ route('vehiculos.show', $vehiculo->id_vehiculo) }}" 
                                   class="btn btn-premium btn-sm text-uppercase fw-bold tracking-wider py-2">
                                    Ver Detalles
                                </a>
                            </div>

                            {{-- Acciones de Administración y Dueños --}}
                            @if(Auth::user()->tipo_usuario === 'admin' || Auth::user()->id_usuario === $vehiculo->id_vendedor)
                                <div class="vehicle-actions-admin d-flex justify-content-between align-items-center gap-2 mt-3">
                                    <a href="{{ route('vehiculos.edit', $vehiculo->id_vehiculo) }}"
                                       class="btn btn-sm btn-outline-secondary w-100 py-1">
                                        Editar
                                    </a>

                                    @if($vehiculo->estado !== 'vendido')
                                        <form method="POST"
                                              action="{{ route('vehiculos.destroy', $vehiculo->id_vehiculo) }}"
                                              class="w-100"
                                              onsubmit="return confirm('¿Seguro que querés eliminar este vehículo del catálogo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-1">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card card-premium text-center py-5 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-0 fs-5">No encontramos vehículos que coincidan con los filtros de búsqueda.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection