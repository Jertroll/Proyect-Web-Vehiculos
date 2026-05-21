@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Pagos</h4>
        <a href="{{ route('ubicaciones.create') }}" class="btn btn-dark">
            + Nuevo pago
        </a>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pagos.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="pendiente"   {{ request('estado') == 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                            <option value="completado"  {{ request('estado') == 'completado'  ? 'selected' : '' }}>Completado</option>
                            <option value="rechazado"   {{ request('estado') == 'rechazado'   ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="metodo_pago" class="form-select">
                            <option value="">Todos los métodos</option>
                            <option value="efectivo"      {{ request('metodo_pago') == 'efectivo'      ? 'selected' : '' }}>Efectivo</option>
                            <option value="tarjeta"       {{ request('metodo_pago') == 'tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                            <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary w-100">X</a>
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
                        <th>Compra</th>
                        <th>Método</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $pago)
                        <tr>
                            <td>{{ $pago->id_pago }}</td>
                            <td>#{{ $pago->compra->id_compra ?? '—' }}</td>
                            <td>{{ ucfirst($pago->metodo_pago) }}</td>
                            <td>${{ number_format($pago->monto, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $badge = match($pago->estado) {
                                        'completado' => 'bg-success',
                                        'rechazado'  => 'bg-danger',
                                        default      => 'bg-warning text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($pago->estado) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('pagos.show', $pago->id_pago) }}"
                                   class="btn btn-sm btn-outline-primary">Ver</a>

                                @if(Auth::user()->tipo_usuario === 'admin')
                                    <a href="{{ route('pagos.edit', $pago->id_pago) }}"
                                       class="btn btn-sm btn-outline-secondary">Editar</a>

                                    <form method="POST"
                                          action="{{ route('pagos.destroy', $pago->id_pago) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Seguro que querés eliminar este pago?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay pagos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection