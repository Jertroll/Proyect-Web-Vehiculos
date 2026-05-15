@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Nuevo usuario</h5>
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

                    <form method="POST" action="{{ route('usuarios.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" name="nombre" id="nombre"
                                   value="{{ old('nombre') }}"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">
                                Teléfono <span class="text-muted small">(opcional)</span>
                            </label>
                            <input type="text" name="telefono" id="telefono"
                                   value="{{ old('telefono') }}"
                                   class="form-control @error('telefono') is-invalid @enderror">
                            @error('telefono')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipo_usuario" class="form-label">Tipo de usuario</label>
                            <select name="tipo_usuario" id="tipo_usuario"
                                    class="form-select @error('tipo_usuario') is-invalid @enderror"
                                    required>
                                <option value="" disabled {{ old('tipo_usuario') ? '' : 'selected' }}>Seleccioná...</option>
                                <option value="cliente"  {{ old('tipo_usuario') == 'cliente'  ? 'selected' : '' }}>Cliente</option>
                                <option value="vendedor" {{ old('tipo_usuario') == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                                <option value="admin"    {{ old('tipo_usuario') == 'admin'    ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('tipo_usuario')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                Guardar usuario
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection