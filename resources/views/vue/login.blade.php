@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Módulo Vue — Iniciar sesión</h5>
                </div>
                <div class="card-body p-4">

                    {{-- Nota informativa --}}
                    <div class="alert alert-info mb-4">
                        <small>
                            Esta es una autenticación <strong>independiente</strong>
                            del sistema principal, implementada con el método
                            de la semana 8 usando <code>Auth::guard('vue')</code>.
                        </small>
                    </div>

                    {{-- Mensaje de error general --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('vue.login.post') }}">
                        @csrf

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
                            <label for="password" class="form-label">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
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

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">
                                Ingresar al módulo Vue
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-footer text-center text-muted small py-3">
                    ¿Querés volver al sistema principal?
                    <a href="{{ route('home') }}">Ir al dashboard</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection