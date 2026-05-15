@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Editar vehículo</h5>
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

                    <form method="POST" action="{{ route('vehiculos.update', $vehiculo->id_vehiculo) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" name="marca" id="marca"
                                       value="{{ old('marca', $vehiculo->marca) }}"
                                       class="form-control @error('marca') is-invalid @enderror"
                                       required>
                                @error('marca')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" name="modelo" id="modelo"
                                       value="{{ old('modelo', $vehiculo->modelo) }}"
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
                                       value="{{ old('anio', $vehiculo->anio) }}"
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
                                       value="{{ old('precio', $vehiculo->precio) }}"
                                       class="form-control @error('precio') is-invalid @enderror"
                                       min="0" step="0.01"
                                       required>
                                @error('precio')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado"
                                    class="form-select @error('estado') is-invalid @enderror"
                                    required>
                                <option value="disponible" {{ old('estado', $vehiculo->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="vendido"    {{ old('estado', $vehiculo->estado) == 'vendido'    ? 'selected' : '' }}>Vendido</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
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
                                        {{ old('id_ubicacion', $vehiculo->id_ubicacion) == $ubicacion->id_ubicacion ? 'selected' : '' }}>
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
                                      class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $vehiculo->descripcion) }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vehiculos.show', $vehiculo->id_vehiculo) }}"
                               class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                Guardar cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection