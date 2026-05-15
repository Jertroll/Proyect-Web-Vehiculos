@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Editar ubicación</h5>
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

                    <form method="POST"
                          action="{{ route('ubicaciones.update', $ubicacion->id_ubicacion) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" name="ciudad" id="ciudad"
                                       value="{{ old('ciudad', $ubicacion->ciudad) }}"
                                       class="form-control @error('ciudad') is-invalid @enderror"
                                       required>
                                @error('ciudad')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" name="pais" id="pais"
                                       value="{{ old('pais', $ubicacion->pais) }}"
                                       class="form-control @error('pais') is-invalid @enderror"
                                       required>
                                @error('pais')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">
                                Dirección <span class="text-muted small">(opcional)</span>
                            </label>
                            <input type="text" name="direccion" id="direccion"
                                   value="{{ old('direccion', $ubicacion->direccion) }}"
                                   class="form-control @error('direccion') is-invalid @enderror">
                            @error('direccion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitud" class="form-label">
                                    Latitud <span class="text-muted small">(opcional)</span>
                                </label>
                                <input type="number" name="latitud" id="latitud"
                                       value="{{ old('latitud', $ubicacion->latitud) }}"
                                       class="form-control @error('latitud') is-invalid @enderror"
                                       step="0.000001" min="-90" max="90">
                                @error('latitud')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitud" class="form-label">
                                    Longitud <span class="text-muted small">(opcional)</span>
                                </label>
                                <input type="number" name="longitud" id="longitud"
                                       value="{{ old('longitud', $ubicacion->longitud) }}"
                                       class="form-control @error('longitud') is-invalid @enderror"
                                       step="0.000001" min="-180" max="180">
                                @error('longitud')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="codigo_postal" class="form-label">
                                Código postal <span class="text-muted small">(opcional)</span>
                            </label>
                            <input type="text" name="codigo_postal" id="codigo_postal"
                                   value="{{ old('codigo_postal', $ubicacion->codigo_postal) }}"
                                   class="form-control @error('codigo_postal') is-invalid @enderror">
                            @error('codigo_postal')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ubicaciones.index') }}"
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