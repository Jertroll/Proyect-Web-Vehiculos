@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Nueva Compra</h5>
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

                    <form method="POST" action="{{ route('compras.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="id_vehiculo" class="form-label">Vehículo</label>
                            <select name="id_vehiculo" id="id_vehiculo"
                                    class="form-select @error('id_vehiculo') is-invalid @enderror"
                                    required>
                                <option value="">Seleccioná un vehículo</option>
                                @foreach($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id_vehiculo }}"
                                            data-precio="{{ $vehiculo->precio }}"
                                        {{ old('id_vehiculo', $vehiculoSeleccionado?->id_vehiculo) == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                        {{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio }})
                                        — ${{ number_format($vehiculo->precio, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_vehiculo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Toggle pagar ahora --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       id="pagar_ahora" name="pagar_ahora" value="1"
                                       {{ old('pagar_ahora') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="pagar_ahora">
                                    Pagar ahora
                                </label>
                            </div>
                        </div>

                        {{-- Panel de pago-}}
                        <div id="panel_pago" class="{{ old('pagar_ahora') ? '' : 'd-none' }}">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Datos del pago</h6>
                                </div>
                                <div class="card-body">

                                    <p class="text-muted mb-2" id="precio_referencia"></p>

                                    <div class="mb-3">
                                        <label for="metodo_pago" class="form-label">Método de pago</label>
                                        <select name="metodo_pago" id="metodo_pago"
                                                class="form-select @error('metodo_pago') is-invalid @enderror">
                                            <option value="">Seleccioná un método</option>
                                            <option value="efectivo"      {{ old('metodo_pago') == 'efectivo'      ? 'selected' : '' }}>Efectivo</option>
                                            <option value="tarjeta"       {{ old('metodo_pago') == 'tarjeta'       ? 'selected' : '' }}>Tarjeta</option>
                                            <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                                        </select>
                                        @error('metodo_pago')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-2">
                                        <label for="monto_display" class="form-label">Monto a pagar ($)</label>
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
                                        <div class="form-text" id="monto_hint"></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                Confirmar compra
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const toggle       = document.getElementById('pagar_ahora');
    const panel        = document.getElementById('panel_pago');
    const selectVeh    = document.getElementById('id_vehiculo');
    const precioRef    = document.getElementById('precio_referencia');
    const montoHint    = document.getElementById('monto_hint');
    const montoDisplay = document.getElementById('monto_display');
    const montoHidden  = document.getElementById('monto');
    const metodo       = document.getElementById('metodo_pago');

    // Mostrar/ocultar panel de pago
    toggle.addEventListener('change', function () {
        panel.classList.toggle('d-none', !this.checked);
        // Hacer required los campos solo si el panel está visible
        metodo.required = this.checked;
        montoDisplay.required = this.checked;
        updatePrecioRef();
    });

    // Sincronizar precio de referencia al cambiar vehículo
    selectVeh.addEventListener('change', updatePrecioRef);

    function updatePrecioRef() {
        const opt    = selectVeh.options[selectVeh.selectedIndex];
        const precio = opt ? parseFloat(opt.dataset.precio) : null;

        if (precio && toggle.checked) {
            precioRef.textContent = 'Precio del vehículo: $' +
                precio.toLocaleString('en-US', { minimumFractionDigits: 2 });
            montoHint.textContent =
                'Podés pagar el total o un monto menor (quedará como pago pendiente).';
        } else {
            precioRef.textContent = '';
            montoHint.textContent = '';
        }
    }

    // Validar que el monto no supere el precio del vehículo
    document.querySelector('form').addEventListener('submit', function (e) {
        if (!toggle.checked) return;

        const opt    = selectVeh.options[selectVeh.selectedIndex];
        const precio = opt ? parseFloat(opt.dataset.precio) : null;
        const monto  = parseFloat(montoHidden.value);

        if (!precio || isNaN(monto) || monto <= 0) {
            e.preventDefault();
            alert('Ingresá un monto válido.');
            return;
        }

        if (monto > precio) {
            e.preventDefault();
            alert('El monto no puede superar el precio del vehículo ($' +
                precio.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ').');
            montoDisplay.focus();
        }
    });

    // Formato del campo monto
    montoDisplay.addEventListener('input', function () {
        let raw = this.value.replace(/[^0-9.]/g, '');
        const parts = raw.split('.');
        if (parts.length > 2) raw = parts[0] + '.' + parts.slice(1).join('');
        if (parts[1] !== undefined) raw = parts[0] + '.' + parts[1].slice(0, 2);

        const intPart = raw.split('.')[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        const decPart = raw.includes('.') ? '.' + (raw.split('.')[1] ?? '') : '';
        this.value = intPart + decPart;
        montoHidden.value = raw;
    });

    // Inicializar estado al cargar
    if (toggle.checked) {
        metodo.required = true;
        updatePrecioRef();
    }
</script>
@endsection