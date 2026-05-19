@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalle de Compra #{{ $compra->id_compra }}</h5>
                    <a href="{{ route('compras.index') }}" class="btn btn-sm btn-outline-light">Volver</a>
                </div>
                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Vehículo:</strong>
                                {{ $compra->vehiculo->marca ?? '—' }} {{ $compra->vehiculo->modelo ?? '' }}
                                ({{ $compra->vehiculo->anio ?? '' }})
                            </p>
                            <p><strong>Comprador:</strong> {{ $compra->usuario->nombre ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Precio final:</strong> ${{ number_format($compra->precio_final, 2) }}</p>
                            <p><strong>Fecha de compra:</strong>
                                {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Estado:</strong>
                        @php
                            $badge = match($compra->estado) {
                                'pagado'    => 'bg-success',
                                'cancelado' => 'bg-danger',
                                default     => 'bg-warning text-dark',
                            };
                        @endphp
                        <span class="badge {{ $badge }}">{{ ucfirst($compra->estado) }}</span>
                    </div>

                    {{-- Botones para compra pendiente --}}
                    @if($compra->estado === 'pendiente' && Auth::user()->id_usuario === $compra->id_usuario)
                        <div class="d-flex gap-2 mb-4">
                            <a href="{{ route('pagos.create', ['id_compra' => $compra->id_compra]) }}"
                               class="btn btn-success w-100">
                                Pagar ahora
                            </a>

                            <form method="POST"
                                  action="{{ route('compras.update', $compra->id_compra) }}"
                                  class="w-100"
                                  onsubmit="return confirm('¿Seguro que querés cancelar esta compra?')">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="precio_final" value="{{ $compra->precio_final }}">
                                <input type="hidden" name="fecha_compra" value="{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('Y-m-d') }}">
                                <input type="hidden" name="estado" value="cancelado">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    Cancelar compra
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Pagos asociados --}}
                    @if($compra->pagos->count() > 0)
                        <hr>
                        <h6 class="mb-3">Pagos asociados</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Método</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compra->pagos as $pago)
                                    <tr>
                                        <td>{{ $pago->id_pago }}</td>
                                        <td>{{ ucfirst($pago->metodo_pago) }}</td>
                                        <td>${{ number_format($pago->monto, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $pago->estado === 'completado' ? 'bg-success' : ($pago->estado === 'rechazado' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                {{ ucfirst($pago->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No hay pagos registrados para esta compra.</p>
                    @endif

                    @if(Auth::user()->tipo_usuario === 'admin')
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('compras.edit', $compra->id_compra) }}"
                               class="btn btn-outline-secondary">Editar</a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection