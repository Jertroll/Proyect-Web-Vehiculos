@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalle de Favorito #{{ $favorito->id_favorito }}</h5>
                    <a href="{{ route('favoritos.index') }}" class="btn btn-sm btn-outline-light">Volver</a>
                </div>
                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Vehículo:</strong>
                                {{ $favorito->vehiculo->marca ?? '—' }} {{ $favorito->vehiculo->modelo ?? '' }}
                                ({{ $favorito->vehiculo->anio ?? '' }})
                            </p>
                            <p><strong>Precio:</strong>
                                ${{ number_format($favorito->vehiculo->precio ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha agregado:</strong>
                                {{ \Carbon\Carbon::parse($favorito->fecha_agregado)->format('d/m/Y') }}
                            </p>
                            <p><strong>Estado:</strong>
                            <span class="badge {{ $favorito->estado ? 'bg-success' : 'bg-secondary' }}">
                               {{ $favorito->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Nota:</strong>
                        <p class="text-muted mt-1">{{ $favorito->nota ?? 'Sin nota.' }}</p>
                    </div>

                    <div class="d-flex justify-content-between">
                        @if($favorito->vehiculo->estado === 'disponible')
                            <a href="{{ route('compras.create', ['id_vehiculo' => $favorito->vehiculo->id_vehiculo]) }}"
                               class="btn btn-success">
                                Comprar
                            </a>
                        @else
                            <span class="badge bg-secondary p-2">Vehículo no disponible</span>
                        @endif
                        <a href="{{ route('favoritos.edit', $favorito->id_favorito) }}"
                           class="btn btn-outline-secondary">Editar</a>

                        <form method="POST"
                              action="{{ route('favoritos.destroy', $favorito->id_favorito) }}"
                              onsubmit="return confirm('¿Seguro que querés eliminar este favorito?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection