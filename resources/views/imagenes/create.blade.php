@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Agregar imagen</h5>
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

                    <form method="POST" action="{{ route('imagenes-vehiculo.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="id_vehiculo" class="form-label">Vehículo</label>
                            <select name="id_vehiculo" id="id_vehiculo"
                                    class="form-select @error('id_vehiculo') is-invalid @enderror"
                                    required>
                                <option value="" disabled {{ $id_vehiculo ? '' : 'selected' }}>
                                    Seleccioná un vehículo...
                                </option>
                                @foreach($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id_vehiculo }}"
                                        {{ (old('id_vehiculo', $id_vehiculo) == $vehiculo->id_vehiculo) ? 'selected' : '' }}>
                                        {{ $vehiculo->marca }} {{ $vehiculo->modelo }} ({{ $vehiculo->anio }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_vehiculo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="url_imagen" class="form-label">URL de la imagen</label>
                            <input type="url" name="url_imagen" id="url_imagen"
                                   value="{{ old('url_imagen') }}"
                                   class="form-control @error('url_imagen') is-invalid @enderror"
                                   placeholder="https://ejemplo.com/imagen.jpg"
                                   required>
                            @error('url_imagen')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Previa de la imagen al escribir la URL --}}
                        <div class="mb-3" id="preview-container" style="display:none;">
                            <label class="form-label">Previa</label>
                            <img id="preview-img" src=""
                                 class="img-fluid rounded"
                                 style="max-height: 200px; object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                Descripción <span class="text-muted small">(opcional)</span>
                            </label>
                            <input type="text" name="descripcion" id="descripcion"
                                   value="{{ old('descripcion') }}"
                                   class="form-control @error('descripcion') is-invalid @enderror"
                                   placeholder="Ej: Vista frontal del vehículo">
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="orden" class="form-label">Orden de presentación</label>
                            <input type="number" name="orden" id="orden"
                                   value="{{ old('orden', 0) }}"
                                   class="form-control @error('orden') is-invalid @enderror"
                                   min="0" required>
                            <div class="form-text">
                                0 = primera imagen, 1 = segunda, etc.
                            </div>
                            @error('orden')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('imagenes-vehiculo.index') }}"
                               class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                Guardar imagen
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/imagenes.js') }}"></script>
@endpush