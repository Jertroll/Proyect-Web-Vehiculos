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
@endsection