@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Registrar Pago</h5>
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

                    <form method="POST" action="{{ route('pagos.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="id_compra" class="form-label">Compra</label>
                            <select name="id_compra" id="id_compra"
                                    class="form-select @error('id_compra') is-invalid @enderror"
                                    required>
                                <option value="">Seleccioná una compra</option>
                                @foreach($compras as $compra)
                                    <option value="{{ $compra->id_compra }}"
                                        {{ old('id_compra', $compraSeleccionada?->id_compra) == $compra->id_compra ? 'selected' : '' }}>
                                        #{{ $compra->id_compra }} —
                                        {{ $compra->vehiculo->marca ?? '—' }} {{ $compra->vehiculo->modelo ?? '' }}
                                        (${{ number_format($compra->precio_final, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_compra')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de pago</label>
                            <select name="metodo_pago" id="metodo_pago"
                                    class="form-select @error('metodo_pago') is-invalid @enderror"
                                    required>
                                <option value="">Seleccioná un método</option>
                                <option value="efectivo"      {{ old('metodo_pago') == 'efectivo'      ? 'selected' : '' }}>Efectivo</option>
                                <option value="tarjeta"       {{ old('metodo_pago') == 'tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                                <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                            @error('metodo_pago')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="monto_display" class="form-label">Monto ($)</label>
                            <input type="text" id="monto_display"
                                   class="form-control @error('monto') is-invalid @enderror"
                                   placeholder="0.00"
                                   value="{{ old('monto') ? number_format(old('monto'), 2) : '' }}"
                                   autocomplete="off">
                            <input type="hidden" name="monto" id="monto"
                                   value="{{ old('monto') }}">
                            @error('monto')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                Registrar pago
                            </button>
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
        // Eliminar todo excepto dígitos y punto
        let raw = this.value.replace(/[^0-9.]/g, '');

        // Evitar múltiples puntos
        const parts = raw.split('.');
        if (parts.length > 2) raw = parts[0] + '.' + parts.slice(1).join('');

        // Limitar a 2 decimales
        if (parts[1] !== undefined) {
            raw = parts[0] + '.' + parts[1].slice(0, 2);
        }

        // Formatear la parte entera con comas
        const intPart   = raw.split('.')[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        const decPart   = raw.includes('.') ? '.' + (raw.split('.')[1] ?? '') : '';
        this.value      = intPart + decPart;

        // Guardar valor numérico limpio en el hidden
        hidden.value = raw;
    });
</script>
@endsection