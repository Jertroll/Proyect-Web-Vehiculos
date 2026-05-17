@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Imágenes de vehículos</h4>
        <a href="{{ route('imagenes-vehiculo.create') }}" class="btn btn-dark">
            + Agregar imagen
        </a>
    </div>

    {{-- Filtro por vehículo --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('imagenes-vehiculo.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <select name="id_vehiculo" class="form-select">
                            <option value="">Todos los vehículos</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}"
                                    {{ request('id_vehiculo') == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                        <a href="{{ route('imagenes-vehiculo.index') }}"
                           class="btn btn-outline-secondary w-100">X</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de imágenes --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Vehículo</th>
                        <th>Previa</th>
                        <th>Descripción</th>
                        <th>Orden</th>
                        <th>Fecha subida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($imagenes as $imagen)
                        <tr>
                            <td>{{ $imagen->id_imagen }}</td>
                            <td>
                                {{ $imagen->vehiculo->marca ?? '—' }}
                                {{ $imagen->vehiculo->modelo ?? '' }}
                            </td>
                            <td>
                                <img src="{{ $imagen->url_imagen }}"
                                     alt="{{ $imagen->descripcion }}"
                                     style="width: 80px; height: 55px; object-fit: cover;"
                                     class="rounded">
                            </td>
                            <td>{{ $imagen->descripcion ?? '—' }}</td>
                            <td>{{ $imagen->orden }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($imagen->fecha_subida)->format('d/m/Y') }}
                            </td>
                            <td>
                                <a href="{{ route('imagenes-vehiculo.edit', $imagen->id_imagen) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Editar
                                </a>
                                <form method="POST"
                                      action="{{ route('imagenes-vehiculo.destroy', $imagen->id_imagen) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Seguro que querés eliminar esta imagen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay imágenes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection