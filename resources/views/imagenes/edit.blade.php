@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Editar imagen</h5>
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

                    {{-- Previa actual --}}
                    <div class="mb-3 text-center">
                        <img src="{{ $imagen->url_imagen }}"
                             id="preview-img"
                             class="img-fluid rounded"
                             style="max-height: 200px; object-fit: cover;">
                    </div>

                    <form method="POST"
                          action="{{ route('imagenes-vehiculo.update', $imagen->id_imagen) }}">
                        @csrf
                        @method('PUT')

                        {{-- Vehículo solo lectura en edición --}}
                        <div class="mb-3">
                            <label class="form-label">Vehículo</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $imagen->vehiculo->marca }} {{ $imagen->vehiculo->modelo }} ({{ $imagen->vehiculo->anio }})"
                                   disabled>
                        </div>

                        <div class="mb-3">
                            <label for="url_imagen" class="form-label">URL de la imagen</label>
                            <input type="url" name="url_imagen" id="url_imagen"
                                   value="{{ old('url_imagen', $imagen->url_imagen) }}"
                                   class="form-control @error('url_imagen') is-invalid @enderror"
                                   required>
                            @error('url_imagen')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                Descripción <span class="text-muted small">(opcional)</span>
                            </label>
                            <input type="text" name="descripcion" id="descripcion"
                                   value="{{ old('descripcion', $imagen->descripcion) }}"
                                   class="form-control @error('descripcion') is-invalid @enderror">
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="orden" class="form-label">Orden de presentación</label>
                            <input type="number" name="orden" id="orden"
                                   value="{{ old('orden', $imagen->orden) }}"
                                   class="form-control @error('orden') is-invalid @enderror"
                                   min="0" required>
                            @error('orden')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vehiculos.show', $imagen->id_vehiculo) }}"
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

@push('scripts')
<script src="{{ asset('js/imagenes.js') }}"></script>
@endpush