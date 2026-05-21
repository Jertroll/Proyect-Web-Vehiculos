@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5"> 

            <div class="card card-premium">
                <div class="card-header py-4 text-center">
                    <h4 class="premium-title mb-0">Iniciar sesión</h4>
                </div>

                <div class="card-body p-5">

                    {{-- Mensaje de error general de credenciales --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

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
                                autofocus
                                autocomplete="email"
                                placeholder="correo@ejemplo.com"
                            >
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                required
                                autocomplete="current-password"
                                placeholder="Mínimo 8 caracteres"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Recordarme --}}
                        <div class="mb-4">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <label class="form-check-label text-muted" for="remember">
                                    Recordarme en este dispositivo
                                </label>
                            </div>
                        </div>

                        {{-- Botón submit --}}
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-premium btn-lg fw-bold text-uppercase">
                                Ingresar al sistema
                            </button>
                        </div>

                        {{-- Link a registro --}}
                        <div class="text-center mt-3">
                            <span class="text-muted">
                                ¿Aún no eres cliente?
                                <a href="{{ route('register') }}" class="link-premium ms-1">Solicita tu cuenta</a>
                            </span>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection