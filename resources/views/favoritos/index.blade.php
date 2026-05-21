@extends('layouts.app')

@section('content')
<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Favoritos</h4>
    @if(Auth::user()->tipo_usuario === 'admin')
        <a href="{{ route('favoritos.create') }}" class="btn btn-dark">
            + Nuevo favorito
        </a>
    @endif
</div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('favoritos.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="activo"   {{ request('estado') == 'activo'   ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                        <a href="{{ route('favoritos.index') }}" class="btn btn-outline-secondary w-100">X</a>
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
                        <th>Vehículo</th>
                        <th>Nota</th>
                        <th>Fecha agregado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($favoritos as $favorito)
                        <tr>
                            <td>{{ $favorito->id_favorito }}</td>
                            <td>{{ $favorito->vehiculo->marca ?? '—' }} {{ $favorito->vehiculo->modelo ?? '' }}</td>
                            <td>{{ $favorito->nota ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($favorito->fecha_agregado)->format('d/m/Y') }}</td>
                            <td>
                            <span class="badge {{ $favorito->estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $favorito->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                            </td>
                            <td>
                                <a href="{{ route('favoritos.show', $favorito->id_favorito) }}"
                                   class="btn btn-sm btn-outline-primary">Ver</a>

                                <a href="{{ route('favoritos.edit', $favorito->id_favorito) }}"
                                   class="btn btn-sm btn-outline-secondary">Editar</a>
                                @if($favorito->vehiculo->estado === 'disponible')
                                   <a href="{{ route('compras.create', ['id_vehiculo' => $favorito->vehiculo->id_vehiculo]) }}"
                                      class="btn btn-sm btn-success">
                                      Comprar
                                   </a>
                                @else
                                    <span class="badge bg-secondary">No disponible</span>
                                @endif

                                <form method="POST"
                                      action="{{ route('favoritos.destroy', $favorito->id_favorito) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Seguro que querés eliminar este favorito?')">
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
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay vehículos en favoritos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection