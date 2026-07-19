{{-- Hero Banner foto principal --}}
<div class="hero-banner-wrapper mb-5">
    <div class="hero-banner" style="background-image: url('{{ asset('img/hero-banner.png') }}');">
        <div class="hero-banner-overlay"></div>
        <div class="hero-banner-content">
            <span class="hero-eyebrow text-uppercase fw-semibold">Bienvenido a {{ config('app.name', 'Nuestra Empresa') }}</span>
            <h1 class="hero-title font-serif">
                Encuentra tu <span style="color: var(--color-premium-gold);">vehículo ideal</span>
            </h1>
            <p class="hero-subtitle">
                Calidad, confianza y las mejores opciones del mercado en un solo lugar.
            </p>
            <a href="{{ route('vehiculos.indexCards') }}" class="btn btn-premium btn-lg px-5 text-uppercase fw-bold tracking-wider mt-2">
                Ver Catálogo
            </a>
        </div>
    </div>
</div>

{{-- Hero Banner: Bienvenida al cliente --}}
<div class="card card-welcome-premium shadow-lg mb-5 border-0" style="background: linear-gradient(135deg, var(--color-premium-dark) 0%, #2a2a2a 100%);">
    <div class="card-body p-5 text-center">
        <h2 class="text-white font-serif mb-3">Encuentra el vehículo de tus sueños, <span style="color: var(--color-premium-gold);">{{ $usuario->nombre }}</span></h2>
        <p class="text-white-50 mb-4 fs-5">Explora nuestra colección exclusiva de modelos premium listos para ti.</p>
        <a href="{{ route('vehiculos.indexCards') }}" class="btn btn-premium btn-lg px-5 text-uppercase fw-bold tracking-wider">
            Ver Catálogo Completo
        </a>
    </div>
</div>



{{-- Carrusel: Autos recomendados del día --}}
@if($destacados->count() > 0)
<div class="mb-5">
    <h4 class="font-serif fw-bold text-uppercase mb-4" style="letter-spacing: 0.5px;">
        Autos recomendados del día
    </h4>

    <div id="carruselRecomendados" class="carousel slide recommended-carousel" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            @foreach($destacados as $index => $vehiculo)
                <div class="carousel-item @if($index === 0) active @endif">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-5">
                            <div class="card vehicle-card shadow-sm h-100">

                                <div class="vehicle-img-container">
                                    @if($vehiculo->imagenes && $vehiculo->imagenes->count() > 0)
                                        @php $primeraImagen = $vehiculo->imagenes->first(); @endphp
                                        <img src="{{ $primeraImagen->url_imagen }}"
                                             class="vehicle-img"
                                             alt="{{ $primeraImagen->descripcion ?? $vehiculo->marca }}">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark">
                                            <span class="text-white-50 text-uppercase small tracking-wider">Sin Imagen Disponible</span>
                                        </div>
                                    @endif
                                    <span class="badge-status-disponible">Disponible</span>
                                </div>

                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="vehicle-title mb-0 text-truncate me-2">
                                                {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                            </h5>
                                            <span class="badge bg-light text-dark border fw-bold">{{ $vehiculo->anio }}</span>
                                        </div>

                                        <div class="d-flex align-items-center text-muted small mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-geo-alt-fill me-1 text-premium-gold" viewBox="0 0 16 16">
                                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                            </svg>
                                            {{ $vehiculo->ubicacion->ciudad ?? 'Ubicación no especificada' }}
                                        </div>

                                        <div class="vehicle-card-price mb-3">
                                            ${{ number_format($vehiculo->precio, 2) }}
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <a href="{{ route('vehiculos.show', $vehiculo->id_vehiculo) }}"
                                           class="btn btn-premium btn-sm text-uppercase fw-bold tracking-wider py-2">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carruselRecomendados" data-bs-slide="prev">
            <span class="carousel-control-prev-icon custom-carousel-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carruselRecomendados" data-bs-slide="next">
            <span class="carousel-control-next-icon custom-carousel-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>

        <div class="carousel-indicators position-relative mt-3">
            @foreach($destacados as $index => $vehiculo)
                <button type="button" data-bs-target="#carruselRecomendados" data-bs-slide-to="{{ $index }}"
                        class="custom-carousel-dot @if($index === 0) active @endif"
                        @if($index === 0) aria-current="true" @endif></button>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Sección: Acerca de Nosotros --}}
<div class="card shadow-sm border-0 bg-light mt-5">
    <div class="card-body p-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="font-serif fw-bold mb-3">Compromiso y Excelencia</h4>
                <p class="text-muted mb-0" style="line-height: 1.8;">
                    En <strong>{{ config('app.name', 'Nuestra Empresa') }}</strong>, no solo vendemos vehículos; entregamos experiencias. Nuestro riguroso proceso de selección garantiza que cada automóvil en nuestro catálogo cumple con los más altos estándares de calidad, seguridad y rendimiento. Tu próxima gran aventura comienza aquí.
                </p>
            </div>
            <div class="col-md-4 text-center mt-4 mt-md-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="var(--color-premium-gold)" class="bi bi-shield-check" viewBox="0 0 16 16">
                    <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                    <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                </svg>
            </div>
        </div>
    </div>
</div>