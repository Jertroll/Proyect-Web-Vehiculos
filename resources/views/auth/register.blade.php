@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card card-premium">
                <div class="card-header py-4 text-center">
                    <h4 class="premium-title mb-0">Crear cuenta</h4>
                </div>

                <div class="card-body p-5">

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
                            <strong>Por favor verificá los siguientes datos:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            {{-- Nombre --}}
                            <div class="col-md-6 mb-4">
                                <label for="nombre" class="form-label fw-semibold">
                                    Nombre completo
                                </label>
                                <input
                                    id="nombre"
                                    type="text"
                                    name="nombre"
                                    value="{{ old('nombre') }}"
                                    class="form-control form-control-lg @error('nombre') is-invalid @enderror"
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

                            {{-- Teléfono --}}
                            <div class="col-md-6 mb-4">
                                <label for="telefono" class="form-label fw-semibold">
                                    Teléfono
                                    <span class="text-muted small fw-normal ms-1">(opcional)</span>
                                </label>
                                <input
                                    id="telefono"
                                    type="text"
                                    name="telefono"
                                    value="{{ old('telefono') }}"
                                    class="form-control form-control-lg @error('telefono') is-invalid @enderror"
                                    autocomplete="tel"
                                    placeholder="Ej: 8888-8888"
                                >
                                @error('telefono')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                Correo electrónico
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
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

                        {{-- Tipo de usuario --}}
                        <div class="mb-4">
                            <label for="tipo_usuario" class="form-label fw-semibold">
                                Perfil de usuario
                            </label>
                            <select
                                id="tipo_usuario"
                                name="tipo_usuario"
                                class="form-select form-select-lg @error('tipo_usuario') is-invalid @enderror"
                                required
                            >
                                <option value="" disabled {{ old('tipo_usuario') ? '' : 'selected' }}>
                                    Seleccioná tu rol comercial...
                                </option>
                                <option value="cliente"   {{ old('tipo_usuario') == 'cliente'   ? 'selected' : '' }}>Cliente (Comprar vehículos)</option>
                                <option value="vendedor"  {{ old('tipo_usuario') == 'vendedor'  ? 'selected' : '' }}>Vendedor (Publicar vehículos)</option>
                            </select>
                            @error('tipo_usuario')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Password --}}
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    Contraseña
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
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
                            <div class="col-md-6 mb-5">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Confirmar contraseña
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control form-control-lg"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Repetí la contraseña"
                                >
                            </div>
                        </div>

                        {{-- Botón submit --}}
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-premium btn-lg fw-bold text-uppercase">
                                Completar registro
                            </button>
                        </div>

                        {{-- Link al login --}}
                        <div class="text-center mt-3">
                            <span class="text-muted">
                                ¿Ya tienes una cuenta con nosotros?
                                <a href="{{ route('login') }}" class="link-premium ms-1">Inicia sesión</a>
                            </span>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection