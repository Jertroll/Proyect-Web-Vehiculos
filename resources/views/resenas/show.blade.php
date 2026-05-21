@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalle de Reseña #{{ $resena->id_resena }}</h5>
                    <a href="{{ route('resenas.index') }}" class="btn btn-sm btn-outline-light">Volver</a>
                </div>
                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Vehículo:</strong>
                                {{ $resena->vehiculo->marca ?? '—' }} {{ $resena->vehiculo->modelo ?? '' }}
                                ({{ $resena->vehiculo->anio ?? '' }})
                            </p>
                            <p><strong>Usuario:</strong> {{ $resena->usuario->nombre ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong>
                                {{ \Carbon\Carbon::parse($resena->fecha)->format('d/m/Y') }}
                            </p>
                            <p><strong>Calificación:</strong>
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color: {{ $i <= $resena->calificacion ? '#ffc107' : '#dee2e6' }}; font-size: 1.2rem;">★</span>
                                @endfor
                                ({{ $resena->calificacion }}/5)
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Comentario:</strong>
                        <p class="text-muted mt-1">{{ $resena->comentario ?? 'Sin comentario.' }}</p>
                    </div>

                    @if(Auth::user()->tipo_usuario === 'admin' || Auth::user()->id_usuario === $resena->id_usuario)
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('resenas.edit', $resena->id_resena) }}"
                               class="btn btn-outline-secondary">Editar</a>

                            <form method="POST"
                                  action="{{ route('resenas.destroy', $resena->id_resena) }}"
                                  onsubmit="return confirm('¿Seguro que querés eliminar esta reseña?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection