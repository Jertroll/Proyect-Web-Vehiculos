@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Mis Compras</h4>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('compras.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="pendiente"  {{ request('estado') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado"     {{ request('estado') == 'pagado'     ? 'selected' : '' }}>Pagado</option>
                            <option value="cancelado"  {{ request('estado') == 'cancelado'  ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="fecha_desde" class="form-control"
                               placeholder="Desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="fecha_hasta" class="form-control"
                               placeholder="Hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary w-100">X</a>
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
                        <th>Precio final</th>
                        <th>Fecha de compra</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $compra)
                        <tr>
                            <td>{{ $compra->id_compra }}</td>
                            <td>{{ $compra->vehiculo->marca ?? '—' }} {{ $compra->vehiculo->modelo ?? '' }}</td>
                            <td>${{ number_format($compra->precio_final, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $badge = match($compra->estado) {
                                        'pagado'    => 'bg-success',
                                        'cancelado' => 'bg-danger',
                                        default     => 'bg-warning text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($compra->estado) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('compras.show', $compra->id_compra) }}"
                                   class="btn btn-sm btn-outline-primary">Ver</a>

                                @if(Auth::user()->tipo_usuario === 'admin')
                                    <a href="{{ route('compras.edit', $compra->id_compra) }}"
                                       class="btn btn-sm btn-outline-secondary">Editar</a>

                                    @if($compra->estado !== 'pagado')
                                        <form method="POST"
                                              action="{{ route('compras.destroy', $compra->id_compra) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Seguro que querés eliminar esta compra?')">
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
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay compras registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection