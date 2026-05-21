@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Editar Favorito #{{ $favorito->id_favorito }}</h5>
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

                    <form method="POST" action="{{ route('favoritos.update', $favorito->id_favorito) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Vehículo</label>
                            <input type="text" class="form-control" disabled
                                   value="{{ $favorito->vehiculo->marca ?? '—' }} {{ $favorito->vehiculo->modelo ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado"
                                    class="form-select @error('estado') is-invalid @enderror"
                                    required>
                                <option value="1" {{ old('estado', $favorito->estado) == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('estado', $favorito->estado) == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nota" class="form-label">
                                Nota <span class="text-muted small">(opcional)</span>
                            </label>
                            <textarea name="nota" id="nota" rows="3"
                                      class="form-control @error('nota') is-invalid @enderror">{{ old('nota', $favorito->nota) }}</textarea>
                            @error('nota')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('favoritos.show', $favorito->id_favorito) }}"
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