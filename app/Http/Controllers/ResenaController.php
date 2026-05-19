<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar reseñas
    public function index(Request $request)
    {
        $query = Resena::with(['usuario', 'vehiculo']);

        // Un usuario normal solo ve sus propias reseñas
        if (Auth::user()->tipo_usuario !== 'admin') {
            $query->where('id_usuario', Auth::user()->id_usuario);
        }

        // Filtro por calificación
        if ($request->filled('calificacion')) {
            $query->where('calificacion', $request->calificacion);
        }

        // Filtro por vehículo
        if ($request->filled('id_vehiculo')) {
            $query->where('id_vehiculo', $request->id_vehiculo);
        }

        $resenas = $query->orderBy('fecha', 'desc')->get();

        return view('resenas.index', compact('resenas'));
    }

    // Ver detalle de una reseña
    public function show(string $id)
    {
        $resena = Resena::with(['usuario', 'vehiculo'])->findOrFail($id);

        return view('resenas.show', compact('resena'));
    }

    // Formulario de creación
    public function create(Request $request)
    {
        $vehiculos = Vehiculo::orderBy('marca')->get();

        $vehiculoSeleccionado = null;
        if ($request->filled('id_vehiculo')) {
            $vehiculoSeleccionado = Vehiculo::findOrFail($request->id_vehiculo);
        }

        return view('resenas.create', compact('vehiculos', 'vehiculoSeleccionado'));
    }

    // Guardar nueva reseña
    public function store(Request $request)
{
    $request->validate([
        'id_vehiculo'  => ['required', 'exists:vehiculos,id_vehiculo'],
        'calificacion' => ['required', 'integer', 'min:1', 'max:5'],
        'comentario'   => ['nullable', 'string', 'max:500'],
    ]);

    // Verificar que el usuario haya comprado y pagado el vehículo
    $comproPagado = \App\Models\Compra::where('id_usuario', Auth::user()->id_usuario)
                    ->where('id_vehiculo', $request->id_vehiculo)
                    ->where('estado', 'pagado')
                    ->exists();

    if (!$comproPagado) {
        return redirect()->back()
            ->with('error', 'Solo podés reseñar vehículos que hayas comprado y pagado.')
            ->withInput();
    }

    // Verificar que el usuario no haya reseñado ya ese vehículo
    $existe = Resena::where('id_usuario', Auth::user()->id_usuario)
                    ->where('id_vehiculo', $request->id_vehiculo)
                    ->exists();

    if ($existe) {
        return redirect()->back()
            ->with('error', 'Ya has dejado una reseña para este vehículo.')
            ->withInput();
    }

    try {
        Resena::create([
            'id_usuario'   => Auth::user()->id_usuario,
            'id_vehiculo'  => $request->id_vehiculo,
            'calificacion' => $request->calificacion,
            'comentario'   => $request->comentario,
            'fecha'        => now(),
        ]);

        return redirect()->route('resenas.index')
            ->with('success', 'Reseña publicada correctamente.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error al publicar la reseña. Intentá de nuevo.')
            ->withInput();
    }
}

    // Formulario de edición
    public function edit(string $id)
    {
        $resena = Resena::findOrFail($id);

        // Solo el autor o un admin puede editar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $resena->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('resenas.index')
                ->with('error', 'No tenés permisos para editar esta reseña.');
        }

        return view('resenas.edit', compact('resena'));
    }

    // Actualizar reseña
    public function update(Request $request, string $id)
    {
        $resena = Resena::findOrFail($id);

        // Solo el autor o un admin puede actualizar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $resena->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('resenas.index')
                ->with('error', 'No tenés permisos para editar esta reseña.');
        }

        $request->validate([
            'calificacion' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario'   => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $resena->update([
                'calificacion' => $request->calificacion,
                'comentario'   => $request->comentario,
            ]);

            return redirect()->route('resenas.index')
                ->with('success', 'Reseña actualizada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la reseña. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Eliminar reseña
    public function destroy(string $id)
    {
        $resena = Resena::findOrFail($id);

        // Solo el autor o un admin puede eliminar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $resena->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('resenas.index')
                ->with('error', 'No tenés permisos para eliminar esta reseña.');
        }

        try {
            $resena->delete();

            return redirect()->route('resenas.index')
                ->with('success', 'Reseña eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la reseña. Intentá de nuevo.');
        }
    }
}