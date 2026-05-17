@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Publicar vehículo</h5>
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

                    <form method="POST" action="{{ route('vehiculos.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" name="marca" id="marca"
                                       value="{{ old('marca') }}"
                                       class="form-control @error('marca') is-invalid @enderror"
                                       required>
                                @error('marca')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" name="modelo" id="modelo"
                                       value="{{ old('modelo') }}"
                                       class="form-control @error('modelo') is-invalid @enderror"
                                       required>
                                @error('modelo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="anio" class="form-label">Año</label>
                                <input type="number" name="anio" id="anio"
                                       value="{{ old('anio') }}"
                                       class="form-control @error('anio') is-invalid @enderror"
                                       min="1900" max="{{ date('Y') }}"
                                       required>
                                @error('anio')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="precio" class="form-label">Precio ($)</label>
                                <input type="number" name="precio" id="precio"
                                       value="{{ old('precio') }}"
                                       class="form-control @error('precio') is-invalid @enderror"
                                       min="0" step="0.01"
                                       required>
                                @error('precio')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="id_ubicacion" class="form-label">
                                Ubicación <span class="text-muted small">(opcional)</span>
                            </label>
                            <select name="id_ubicacion" id="id_ubicacion"
                                    class="form-select @error('id_ubicacion') is-invalid @enderror">
                                <option value="">Sin ubicación asignada</option>
                                @foreach($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->id_ubicacion }}"
                                        {{ old('id_ubicacion') == $ubicacion->id_ubicacion ? 'selected' : '' }}>
                                        {{ $ubicacion->ciudad }}, {{ $ubicacion->pais }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_ubicacion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label">
                                Descripción <span class="text-muted small">(opcional)</span>
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="4"
                                      class="form-control @error('descripcion') is-invalid @enderror"
                                      placeholder="Describí el estado, equipamiento y cualquier detalle relevante del vehículo...">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                Publicar vehículo
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection