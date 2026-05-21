<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Proyecto Vehículos') }}</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Tipografías de Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    {{-- Cargar app.css y app.js mediante Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-md navbar-dark navbar-premium shadow">
        <div class="container">

            {{-- Logo / Nombre de la app --}}
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name', 'Proyecto Vehículos') }}
            </a>

            {{-- Botón colapsar en móvil --}}
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">

                @auth
                <ul class="navbar-nav me-auto">
                    @if (Auth::user()->tipo_usuario !== 'cliente')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                        </li>
                    @endif


                    {{-- Catálogo en formato de tarjetas --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vehiculos.indexCards') }}">Catálogo</a>
                    </li>
                    
                    @if(Auth::user()->tipo_usuario === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('historial.index') }}">Historial</a>
                    </li>
                    @endif

                    {{-- Opción Vue --}}
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('vue.index') }}">Uso de Vue</a>
                    </li>

                    {{-- Menú solo visible para admin --}}
                    @if(Auth::user()->tipo_usuario === 'admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Administración
                        </a>
                        <ul class="dropdown-menu shadow">
                            <li><a class="dropdown-item" href="{{ route('usuarios.index') }}">Usuarios</a></li>
                            <li><a class="dropdown-item" href="{{ route('vehiculos.index') }}">Vehiculos (Tabla)</a></li>
                            <li><a class="dropdown-item" href="{{ route('ubicaciones.index') }}">Ubicaciones</a></li>
                            <li><a class="dropdown-item" href="{{ route('imagenes-vehiculo.index') }}">Imágenes Vehículo</a></li>
                            <li><a class="dropdown-item" href="{{ route('compras.index') }}">Compras General</a></li>
                            <li><a class="dropdown-item" href="{{ route('pagos.index') }}">Pagos General</a></li>
                            <li><a class="dropdown-item" href="{{ route('resenas.index') }}">Reseñas</a></li>
                            <li><a class="dropdown-item" href="{{ route('favoritos.index') }}">Favoritos General</a></li>
                        </ul>
                    </li>
                    @endif

                </ul>
                @endauth

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
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                                {{ Auth::user()->nombre }}
                                <span class="badge badge-premium ms-2">
                                    {{ ucfirst(Auth::user()->tipo_usuario) }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <span class="dropdown-item-text text-white-50 small d-block py-1">
                                        {{ Auth::user()->email }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                @if(Auth::user()->tipo_usuario === 'cliente')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('favoritos.index') }}">
                                            ⭐ Mis Favoritos
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('compras.index') }}">
                                            🛍️ Mis Compras
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pagos.index') }}">
                                            💳 Mis Pagos
                                        </a>
                                    </li>
                                @endif

                                @if(Auth::user()->tipo_usuario === 'cliente' || Auth::user()->tipo_usuario === 'vendedor')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('historial.index') }}">
                                            📜 Mi Historial
                                        </a>
                                    </li>
                                @endif

                                {{-- Separador visual antes del botón de salida si el usuario no es admin --}}
                                @if(Auth::user()->tipo_usuario !== 'admin')
                                    <li><hr class="dropdown-divider"></li>
                                @endif

                                {{-- BOTÓN DE LOGOUT --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-semibold">
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
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Éxito:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong>Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                <strong>Aviso:</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- CONTENIDO DE CADA VISTA --}}
    <main class="container mt-2">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Scripts adicionales --}}
    @stack('scripts')

</body>
</html>