@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Usuarios registrados</h4>
        <a href="{{ route('usuarios.create') }}" class="btn btn-dark">
            + Nuevo usuario
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Fecha registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id_usuario }}</td>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->telefono ?? '—' }}</td>
                            <td>
                                <span class="badge 
                                    {{ $usuario->tipo_usuario === 'admin'   ? 'bg-danger'  : '' }}
                                    {{ $usuario->tipo_usuario === 'vendedor' ? 'bg-warning text-dark' : '' }}
                                    {{ $usuario->tipo_usuario === 'cliente'  ? 'bg-primary' : '' }}
                                ">
                                    {{ ucfirst($usuario->tipo_usuario) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($usuario->fecha_registro)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Editar
                                </a>

                                {{-- No mostrar botón eliminar para el usuario logueado --}}
                                @if(Auth::user()->id_usuario !== $usuario->id_usuario)
                                    <form method="POST"
                                          action="{{ route('usuarios.destroy', $usuario->id_usuario) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Seguro que querés eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection