@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Saludo al usuario --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        Bienvenido, {{ $usuario->nombre }}
                        <span class="badge bg-light text-dark ms-2">
                            {{ ucfirst($usuario->tipo_usuario) }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Sesión iniciada con: <strong>{{ $usuario->email }}</strong>
                    </p>
                </div>
            </div>

            {{-- Tarjetas de resumen según tipo de usuario --}}
            <div class="row">

                @if($usuario->tipo_usuario === 'admin')
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <h3>{{ $datos['total_usuarios'] }}</h3>
                                <p class="mb-0">Usuarios registrados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-secondary">
                            <div class="card-body text-center">
                                <h3>{{ $datos['total_vehiculos'] }}</h3>
                                <p class="mb-0">Vehículos totales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h3>{{ $datos['vehiculos_disponibles'] }}</h3>
                                <p class="mb-0">Disponibles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <h3>{{ $datos['total_compras'] }}</h3>
                                <p class="mb-0">Compras realizadas</p>
                            </div>
                        </div>
                    </div>

                @elseif($usuario->tipo_usuario === 'vendedor')
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <h3>{{ $datos['mis_vehiculos'] }}</h3>
                                <p class="mb-0">Mis vehículos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h3>{{ $datos['disponibles'] }}</h3>
                                <p class="mb-0">Disponibles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <h3>{{ $datos['vendidos'] }}</h3>
                                <p class="mb-0">Vendidos</p>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <h3>{{ $datos['mis_compras'] }}</h3>
                                <p class="mb-0">Mis compras</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h3>{{ $datos['compras_pagas'] }}</h3>
                                <p class="mb-0">Pagadas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <h3>{{ $datos['compras_pendientes'] }}</h3>
                                <p class="mb-0">Pendientes</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection