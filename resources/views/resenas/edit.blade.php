@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Editar Reseña #{{ $resena->id_resena }}</h5>
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

                    <form method="POST" action="{{ route('resenas.update', $resena->id_resena) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Vehículo</label>
                            <input type="text" class="form-control" disabled
                                   value="{{ $resena->vehiculo->marca ?? '—' }} {{ $resena->vehiculo->modelo ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Calificación</label>
                            <div class="d-flex gap-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="calificacion" id="cal_{{ $i }}"
                                               value="{{ $i }}"
                                               {{ old('calificacion', $resena->calificacion) == $i ? 'checked' : '' }}
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
                                      class="form-control @error('comentario') is-invalid @enderror">{{ old('comentario', $resena->comentario) }}</textarea>
                            @error('comentario')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('resenas.show', $resena->id_resena) }}"
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