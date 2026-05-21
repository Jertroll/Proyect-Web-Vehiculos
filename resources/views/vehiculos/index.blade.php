@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Vehículos</h4>
        @if(Auth::user()->tipo_usuario === 'vendedor' || Auth::user()->tipo_usuario === 'admin')
            <a href="{{ route('vehiculos.create') }}" class="btn btn-dark">
                + Publicar vehículo
            </a>
        @endif
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('vehiculos.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="marca" class="form-control"
                               placeholder="Marca" value="{{ request('marca') }}">
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
                        <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary w-100">X</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Listado --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Marca / Modelo</th>
                        <th>Año</th>
                        <th>Precio</th>
                        <th>Ubicación</th>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td>{{ $vehiculo->id_vehiculo }}</td>
                            <td>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</td>
                            <td>{{ $vehiculo->anio }}</td>
                            <td>${{ number_format($vehiculo->precio, 2) }}</td>
                            <td>{{ $vehiculo->ubicacion->ciudad ?? '—' }}</td>
                            <td>{{ $vehiculo->vendedor->nombre ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $vehiculo->estado === 'disponible' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($vehiculo->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('vehiculos.show', $vehiculo->id_vehiculo) }}"
                                   class="btn btn-sm btn-outline-primary">Ver</a>

                                @if(Auth::user()->tipo_usuario === 'admin' || Auth::user()->id_usuario === $vehiculo->id_vendedor)
                                    <a href="{{ route('vehiculos.edit', $vehiculo->id_vehiculo) }}"
                                       class="btn btn-sm btn-outline-secondary">Editar</a>
                                {{--El administrador solo podra eliminar aquellos vehiculos que no esten vendidos --}}
                                    @if($vehiculo->estado !== 'vendido')
                                        <form method="POST"
                                              action="{{ route('vehiculos.destroy', $vehiculo->id_vehiculo) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Seguro que querés eliminar este vehículo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay vehículos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection