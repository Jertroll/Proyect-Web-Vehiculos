@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Crear cuenta</h5>
                </div>

                <div class="card-body p-4">

                    {{-- Mensaje de error general --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Resumen de todos los errores de validación --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Por favor corregí los siguientes errores:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre completo
                            </label>
                            <input
                                id="nombre"
                                type="text"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                class="form-control @error('nombre') is-invalid @enderror"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Ej: Juan Pérez"
                            >
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Correo electrónico
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autocomplete="email"
                                placeholder="correo@ejemplo.com"
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div class="mb-3">
                            <label for="telefono" class="form-label">
                                Teléfono
                                <span class="text-muted small">(opcional)</span>
                            </label>
                            <input
                                id="telefono"
                                type="text"
                                name="telefono"
                                value="{{ old('telefono') }}"
                                class="form-control @error('telefono') is-invalid @enderror"
                                autocomplete="tel"
                                placeholder="Ej: 8888-8888"
                            >
                            @error('telefono')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Tipo de usuario --}}
                        <div class="mb-3">
                            <label for="tipo_usuario" class="form-label">
                                Tipo de usuario
                            </label>
                            <select
                                id="tipo_usuario"
                                name="tipo_usuario"
                                class="form-select @error('tipo_usuario') is-invalid @enderror"
                                required
                            >
                                <option value="" disabled {{ old('tipo_usuario') ? '' : 'selected' }}>
                                    Seleccioná un tipo...
                                </option>
                                <option value="cliente"   {{ old('tipo_usuario') == 'cliente'   ? 'selected' : '' }}>Cliente</option>
                                <option value="vendedor"  {{ old('tipo_usuario') == 'vendedor'  ? 'selected' : '' }}>Vendedor</option>
                                <option value="admin"     {{ old('tipo_usuario') == 'admin'     ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('tipo_usuario')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                                autocomplete="new-password"
                                placeholder="Mínimo 8 caracteres"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Confirmar password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirmar contraseña
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                                autocomplete="new-password"
                                placeholder="Repetí la contraseña"
                            >
                        </div>

                        {{-- Botón submit --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-dark">
                                Crear cuenta
                            </button>
                        </div>

                        {{-- Link al login --}}
                        <div class="text-center">
                            <span class="text-muted small">
                                ¿Ya tenés cuenta?
                                <a href="{{ route('login') }}">Iniciá sesión aquí</a>
                            </span>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection