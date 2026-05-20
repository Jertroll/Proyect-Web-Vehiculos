@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Historial de Transacciones</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Vendedor</th>
                        <th>Ubicación</th>
                        <th>Precio final</th>
                        <th>Método de pago</th>
                        <th>Estado</th>
                        <th>Reseña</th>
                        <th>Favorito</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $compra)
                        @php
                            $pago     = $compra->pagos->sortByDesc('fecha_pago')->first();
                            $resena   = $compra->vehiculo->resenas
                                            ->where('id_usuario', $compra->id_usuario)
                                            ->first();
                            $favorito = $compra->vehiculo->favoritos
                                            ->where('id_usuario', $compra->id_usuario)
                                            ->first();
                        @endphp
                        <tr>
                            <td>{{ $compra->id_compra }}</td>
                            <td>{{ $compra->usuario->nombre ?? '—' }}</td>
                            <td>
                                {{ $compra->vehiculo->marca ?? '—' }}
                                {{ $compra->vehiculo->modelo ?? '' }}
                                ({{ $compra->vehiculo->anio ?? '' }})
                            </td>
                            <td>{{ $compra->vehiculo->vendedor->nombre ?? '—' }}</td>
                            <td>{{ $compra->vehiculo->ubicacion->ciudad ?? '—' }}</td>
                            <td>${{ number_format($compra->precio_final, 2) }}</td>
                            <td>{{ $pago ? ucfirst($pago->metodo_pago) : '—' }}</td>
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
                            <td nowrap>
                                @if($resena)
                                    @for($i = 1; $i <= 5; $i++)<span style="color: {{ $i <= $resena->calificacion ? '#ffc107' : '#dee2e6' }}">★</span>@endfor
                                @else
                                    <span class="text-muted small">Sin reseña</span>
                                @endif
                            </td>
                            <td>
                                @if($favorito)
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($favorito->fecha_agregado)->format('d/m/Y') }}
                                    </small>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                No hay transacciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection