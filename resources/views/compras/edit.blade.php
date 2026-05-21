@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Editar Compra #{{ $compra->id_compra }}</h5>
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

                    <form method="POST" action="{{ route('compras.update', $compra->id_compra) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Vehículo</label>
                            <input type="text" class="form-control" disabled
                                   value="{{ $compra->vehiculo->marca ?? '—' }} {{ $compra->vehiculo->modelo ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="precio_final" class="form-label">Precio final ($)</label>
                            <input type="number" name="precio_final" id="precio_final"
                                   value="{{ old('precio_final', $compra->precio_final) }}"
                                   class="form-control @error('precio_final') is-invalid @enderror"
                                   min="0" step="0.01" required>
                            @error('precio_final')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fecha_compra" class="form-label">Fecha de compra</label>
                            <input type="date" name="fecha_compra" id="fecha_compra"
                                   value="{{ old('fecha_compra', \Carbon\Carbon::parse($compra->fecha_compra)->format('Y-m-d')) }}"
                                   class="form-control @error('fecha_compra') is-invalid @enderror"
                                   required>
                            @error('fecha_compra')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado"
                                    class="form-select @error('estado') is-invalid @enderror"
                                    required>
                                <option value="pendiente" {{ old('estado', $compra->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="pagado"    {{ old('estado', $compra->estado) == 'pagado'    ? 'selected' : '' }}>Pagado</option>
                                <option value="cancelado" {{ old('estado', $compra->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('compras.show', $compra->id_compra) }}"
                               class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-dark">Guardar cambios</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection