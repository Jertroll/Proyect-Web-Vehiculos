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

    {{-- FOOTER --}}
    @auth
        @if(in_array(Auth::user()->tipo_usuario, ['vendedor', 'cliente']))
            <footer class="footer-premium mt-5">
                <div class="container py-4">
                    <div class="row gy-3 align-items-center">
                        <div class="col-md-6 text-center text-md-start">
                            <h6 class="text-white font-serif mb-1">
                                {{ config('app.name', 'Proyecto Vehículos') }}
                            </h6>
                            <p class="text-white-50 small mb-0">
                                &copy; {{ date('Y') }} {{ config('app.name', 'Proyecto Vehículos') }}. Todos los derechos reservados.
                            </p>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <p class="text-white-50 small mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-envelope-fill me-2" viewBox="0 0 16 16" style="color: var(--color-premium-gold);">
                                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                                </svg>
                                contacto: crAutos06@gmail.com
                            </p>
                            <p class="text-white-50 small mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-telephone-fill me-2" viewBox="0 0 16 16" style="color: var(--color-premium-gold);">
                                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-.71.71c-.74.74-1.842 1.061-2.87.702a18.6 18.6 0 0 1-7.01-6.01C.033 6.87-.288 5.766.452 5.028l.71-.71a1.75 1.75 0 0 1 .723-.435z"/>
                                </svg>
                                +506 5566-3223
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        @endif
    @endauth

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Scripts adicionales --}}
    @stack('scripts')

</body>
</html>