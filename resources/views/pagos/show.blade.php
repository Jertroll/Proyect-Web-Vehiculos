@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalle de Pago #{{ $pago->id_pago }}</h5>
                    <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-light">Volver</a>
                </div>
                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Compra:</strong> #{{ $pago->compra->id_compra ?? '—' }}</p>
                            <p><strong>Vehículo:</strong>
                                {{ $pago->compra->vehiculo->marca ?? '—' }}
                                {{ $pago->compra->vehiculo->modelo ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Método de pago:</strong> {{ ucfirst($pago->metodo_pago) }}</p>
                            <p><strong>Monto:</strong> ${{ number_format($pago->monto, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Fecha de pago:</strong>
                                {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong>
                                @php
                                    $badge = match($pago->estado) {
                                        'completado' => 'bg-success',
                                        'rechazado'  => 'bg-danger',
                                        default      => 'bg-warning text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($pago->estado) }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Formulario completar pago --}}
                    @if($pago->estado === 'pendiente' && Auth::user()->id_usuario === $pago->compra->id_usuario)
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Confirmar pago</h6>
                            </div>
                            <div class="card-body">

                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <p class="text-muted">
                                    Monto máximo permitido:
                                    <strong>${{ number_format($pago->compra->precio_final, 2) }}</strong>
                                </p>

                                <form method="POST" action="{{ route('pagos.update', $pago->id_pago) }}"
                                      onsubmit="return confirm('¿Confirmás el pago?')">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="metodo_pago" value="{{ $pago->metodo_pago }}">
                                    <input type="hidden" name="fecha_pago"  value="{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('Y-m-d') }}">
                                    <input type="hidden" name="estado"      value="completado">

                                    <div class="mb-3">
                                        <label for="monto" class="form-label">Monto a pagar ($)</label>
                                        <input type="number" name="monto" id="monto"
                                               value="{{ old('monto', $pago->monto) }}"
                                               class="form-control @error('monto') is-invalid @enderror"
                                               min="0.01"
                                               step="0.01" required>
                                        @error('monto')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        Confirmar pago
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if(Auth::user()->tipo_usuario === 'admin')
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pagos.edit', $pago->id_pago) }}"
                               class="btn btn-outline-secondary">Editar</a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection