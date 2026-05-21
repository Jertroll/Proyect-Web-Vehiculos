@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- PANEL PARA ADMIN Y VENDEDOR --}}
            @if($usuario->tipo_usuario === 'admin' || $usuario->tipo_usuario === 'vendedor')
                
                {{-- Saludo corporativo elegante --}}
                <div class="card card-welcome-premium shadow-sm mb-5">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h4 class="text-white mb-1 font-serif">
                                Bienvenido, <span style="color: var(--color-premium-gold);">{{ $usuario->nombre }}</span>
                            </h4>
                            <p class="text-white-50 mb-0 small">
                                Sesión activa corporativa: <strong>{{ $usuario->email }}</strong>
                            </p>
                        </div>
                        <div>
                            <span class="badge badge-premium fs-6">
                                Panel {{ ucfirst($usuario->tipo_usuario) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Título de sección --}}
                <div class="mb-4">
                    <h6 class="text-muted text-uppercase letter-spacing-1 fw-bold">Descripción General del Sistema</h6>
                    <hr class="mt-2 opacity-10" style="border-color: var(--color-premium-dark);">
                </div>

                {{-- CONTENEDOR FILA REUBICADO CORRECTAMENTE --}}
                <div class="row g-4">

                    @if($usuario->tipo_usuario === 'admin')
                        {{-- Tarjetas para el Administrador --}}
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stat-premium p-3 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="stat-number mb-2">{{ $datos['total_usuarios'] }}</div>
                                    <p class="stat-label mb-0">Usuarios Registrados</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stat-premium p-3 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="stat-number mb-2">{{ $datos['total_vehiculos'] }}</div>
                                    <p class="stat-label mb-0">Vehículos Totales</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stat-premium p-3 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="stat-number mb-2" style="color: #2ec4b6;">{{ $datos['vehiculos_disponibles'] }}</div>
                                    <p class="stat-label mb-0">Vehiculos Disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stat-premium p-3 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="stat-number mb-2">{{ $datos['total_compras'] }}</div>
                                    <p class="stat-label mb-0">Compras  totales</p>
                                </div>
                            </div>
                        </div>

                    @elseif($usuario->tipo_usuario === 'vendedor')
                        {{-- Tarjetas para el Vendedor --}}
                        <div class="col-md-4">
                            <div class="card card-stat-premium p-4 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="stat-number mb-2">{{ $datos['mis_vehiculos'] }}</div>
                                    <p class="stat-label mb-0">Mis Vehículos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-stat-premium p-4 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="stat-number mb-2" style="color: #2ec4b6;">{{ $datos['disponibles'] }}</div>
                                    <p class="stat-label mb-0">Mis Vehiculos Disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-stat-premium p-4 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="stat-number mb-2">{{ $datos['vendidos'] }}</div>
                                    <p class="stat-label mb-0">Vendidos</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div> 

            {{-- PORTAL EXCLUSIVO PARA EL CLIENTE --}}
            @else
                @include('partials.home-cliente')
            @endif

        </div>
    </div>
</div>
@endsection