@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Mis Reseñas</h4>
        <a href="{{ route('resenas.create') }}" class="btn btn-dark">+ Nueva reseña</a>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('resenas.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="calificacion" class="form-select">
                            <option value="">Todas las calificaciones</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ request('calificacion') == $i ? 'selected' : '' }}>
                                    {{ $i }} estrella{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                        <a href="{{ route('resenas.index') }}" class="btn btn-outline-secondary w-100">X</a>
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
                        <th>Calificación</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resenas as $resena)
                        <tr>
                            <td>{{ $resena->id_resena }}</td>
                            <td>{{ $resena->vehiculo->marca ?? '—' }} {{ $resena->vehiculo->modelo ?? '' }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color: {{ $i <= $resena->calificacion ? '#ffc107' : '#dee2e6' }}">★</span>
                                @endfor
                            </td>
                            <td>{{ Str::limit($resena->comentario, 50) ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($resena->fecha)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('resenas.show', $resena->id_resena) }}"
                                   class="btn btn-sm btn-outline-primary">Ver</a>

                                <a href="{{ route('resenas.edit', $resena->id_resena) }}"
                                   class="btn btn-sm btn-outline-secondary">Editar</a>

                                <form method="POST"
                                      action="{{ route('resenas.destroy', $resena->id_resena) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Seguro que querés eliminar esta reseña?')">
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
                                No hay reseñas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection