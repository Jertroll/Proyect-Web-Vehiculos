@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Editar Pago #{{ $pago->id_pago }}</h5>
                </div>
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pagos.update', $pago->id_pago) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Compra</label>
                            <input type="text" class="form-control" disabled
                                   value="#{{ $pago->compra->id_compra ?? '—' }} —
                                          {{ $pago->compra->vehiculo->marca ?? '' }}
                                          {{ $pago->compra->vehiculo->modelo ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de pago</label>
                            <select name="metodo_pago" id="metodo_pago"
                                    class="form-select @error('metodo_pago') is-invalid @enderror"
                                    required>
                                <option value="efectivo"      {{ old('metodo_pago', $pago->metodo_pago) == 'efectivo'      ? 'selected' : '' }}>Efectivo</option>
                                <option value="tarjeta"       {{ old('metodo_pago', $pago->metodo_pago) == 'tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                                <option value="transferencia" {{ old('metodo_pago', $pago->metodo_pago) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                            @error('metodo_pago')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="monto_display" class="form-label">Monto ($)</label>
                            <input type="text" id="monto_display"
                                   class="form-control @error('monto') is-invalid @enderror"
                                   placeholder="0.00"
                                   value="{{ number_format(old('monto', $pago->monto), 2) }}"
                                   autocomplete="off">
                            <input type="hidden" name="monto" id="monto"
                                   value="{{ old('monto', $pago->monto) }}">
                            @error('monto')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fecha_pago" class="form-label">Fecha de pago</label>
                            <input type="date" name="fecha_pago" id="fecha_pago"
                                   value="{{ old('fecha_pago', \Carbon\Carbon::parse($pago->fecha_pago)->format('Y-m-d')) }}"
                                   class="form-control @error('fecha_pago') is-invalid @enderror"
                                   required>
                            @error('fecha_pago')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado"
                                    class="form-select @error('estado') is-invalid @enderror"
                                    required>
                                <option value="pendiente"  {{ old('estado', $pago->estado) == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                                <option value="completado" {{ old('estado', $pago->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="rechazado"  {{ old('estado', $pago->estado) == 'rechazado'  ? 'selected' : '' }}>Rechazado</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pagos.show', $pago->id_pago) }}"
                               class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-dark">Guardar cambios</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const display = document.getElementById('monto_display');
    const hidden  = document.getElementById('monto');

    display.addEventListener('input', function () {
        let raw = this.value.replace(/[^0-9.]/g, '');

        const parts = raw.split('.');
        if (parts.length > 2) raw = parts[0] + '.' + parts.slice(1).join('');

        if (parts[1] !== undefined) {
            raw = parts[0] + '.' + parts[1].slice(0, 2);
        }

        const intPart   = raw.split('.')[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        const decPart   = raw.includes('.') ? '.' + (raw.split('.')[1] ?? '') : '';
        this.value      = intPart + decPart;

        hidden.value = raw;
    });
</script>
@endsection