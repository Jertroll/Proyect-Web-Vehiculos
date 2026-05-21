@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Nueva Reseña</h5>
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

                    <div class="mb-3">
                        <label class="form-label">Vehículo</label>
                        @if($vehiculoSeleccionado)
                            <input type="text" class="form-control" disabled
                                   value="{{ $vehiculoSeleccionado->marca }} {{ $vehiculoSeleccionado->modelo }} ({{ $vehiculoSeleccionado->anio }})">
                        @else
                            <div class="alert alert-warning mb-0">
                                Debés acceder desde el detalle de un vehículo para dejar una reseña.
                            </div>
                        @endif
                    </div>

                    @if($vehiculoSeleccionado)
                        <form method="POST" action="{{ route('resenas.store') }}">
                            @csrf
                            <input type="hidden" name="id_vehiculo" value="{{ $vehiculoSeleccionado->id_vehiculo }}">

                            <div class="mb-3">
                                <label class="form-label">Calificación</label>
                                <div class="d-flex gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="calificacion" id="cal_{{ $i }}"
                                                   value="{{ $i }}"
                                                   {{ old('calificacion') == $i ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="cal_{{ $i }}">
                                                {{ $i }} ★
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @error('calificacion')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="comentario" class="form-label">
                                    Comentario <span class="text-muted small">(opcional)</span>
                                </label>
                                <textarea name="comentario" id="comentario" rows="4"
                                          class="form-control @error('comentario') is-invalid @enderror"
                                          placeholder="Contá tu experiencia con este vehículo...">{{ old('comentario') }}</textarea>
                                @error('comentario')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('vehiculos.show', $vehiculoSeleccionado->id_vehiculo) }}"
                                   class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-dark">
                                    Publicar reseña
                                </button>
                            </div>

                        </form>
                    @else
                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">
                                Volver a vehículos
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection