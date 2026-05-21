<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Proyecto Vehículos') }}</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">

            {{-- Logo / Nombre de la app --}}
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name', 'Proyecto Vehículos') }}
            </a>

            {{-- Botón colapsar en móvil --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">

                {{-- Links del menú izquierdo - solo visibles si está logueado --}}
                @auth
                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vehiculos.index') }}">Vehículos</a>
                    </li>
                    @if(Auth::user()->tipo_usuario === 'cliente')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('favoritos.index') }}">Mis Favoritos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('compras.index') }}">Mis Compras</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('historial.index') }}">Historial</a>
                    </li>

{{-- Opción Vue - requerida por el enunciado --}}
{{-- Se habilita en la Fase 6 --}}
{{-- 
<li class="nav-item">
    <a class="nav-link" href="{{ route('vue.index') }}">Uso de Vue</a>
</li>
--}}

                    {{-- Menú solo visible para admin --}}
                    @if(Auth::user()->tipo_usuario === 'admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('usuarios.index') }}">Usuarios</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('ubicaciones.index') }}">Ubicaciones</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('imagenes-vehiculo.index') }}">Imágenes Vehículo</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('pagos.index') }}">Pagos</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('resenas.index') }}">Reseñas</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                </ul>
                @endauth

                {{-- Lado derecho del navbar --}}
                <ul class="navbar-nav ms-auto">

                    {{-- Si NO está logueado --}}
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @endguest

                    {{-- Si SÍ está logueado --}}
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                {{ Auth::user()->nombre }}
                                <span class="badge bg-secondary ms-1">
                                    {{ ucfirst(Auth::user()->tipo_usuario) }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text text-muted small">
                                        {{ Auth::user()->email }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                {{-- BOTÓN DE LOGOUT - requerido por el enunciado --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                </ul>

            </div>
        </div>
    </nav>

    {{-- MENSAJES FLASH GLOBALES --}}
    <div class="container mt-3">

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Mensaje de error --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Mensaje de advertencia --}}
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

    </div>

    {{-- CONTENIDO DE CADA VISTA --}}
    <main class="container mt-4">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Scripts adicionales por vista --}}
    @stack('scripts')

</body>
</html>