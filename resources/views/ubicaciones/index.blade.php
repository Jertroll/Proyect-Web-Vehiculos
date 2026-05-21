@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Ubicaciones</h4>
        <a href="{{ route('ubicaciones.create') }}" class="btn btn-dark">
            + Nueva ubicación
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Ciudad</th>
                        <th>País</th>
                        <th>Dirección</th>
                        <th>Coordenadas</th>
                        <th>Código postal</th>
                        <th>Vehículos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ubicaciones as $ubicacion)
                        <tr>
                            <td>{{ $ubicacion->id_ubicacion }}</td>
                            <td>{{ $ubicacion->ciudad }}</td>
                            <td>{{ $ubicacion->pais }}</td>
                            <td>{{ $ubicacion->direccion ?? '—' }}</td>
                            <td>
                                @if($ubicacion->latitud && $ubicacion->longitud)
                                    <span class="text-muted small">
                                        {{ $ubicacion->latitud }}, {{ $ubicacion->longitud }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $ubicacion->codigo_postal ?? '—' }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $ubicacion->vehiculos_count }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('ubicaciones.edit', $ubicacion->id_ubicacion) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Editar
                                </a>

                                @if($ubicacion->vehiculos_count === 0)
                                    <form method="POST"
                                          action="{{ route('ubicaciones.destroy', $ubicacion->id_ubicacion) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Seguro que querés eliminar esta ubicación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-danger"
                                            disabled
                                            title="Tiene vehículos asociados">
                                        Eliminar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay ubicaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection