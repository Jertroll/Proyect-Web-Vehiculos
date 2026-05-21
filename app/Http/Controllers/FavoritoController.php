<?php

namespace App\Http\Controllers;

use App\Models\Favoritos;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar favoritos del usuario autenticado
    public function index(Request $request)
    {
        $query = Favoritos::with(['usuario', 'vehiculo'])
        ->where('id_usuario', Auth::user()->id_usuario);

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $favoritos = $query->orderBy('fecha_agregado', 'desc')->get();

        return view('favoritos.index', compact('favoritos'));
    }


    // Ver detalle de un favorito
    public function show(string $id_favorito)
    {
        $favorito = Favoritos::with(['usuario', 'vehiculo'])
                            ->findOrFail($id_favorito);

        // Solo el dueño o un admin puede ver el detalle
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $favorito->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('favoritos.index')
                ->with('error', 'No tenés permisos para ver este favorito.');
        }

        return view('favoritos.show', compact('favorito'));
    }

    // Formulario de creación
    public function create(Request $request)
    {
        $vehiculos = Vehiculo::where('estado', 'disponible')->orderBy('marca')->get();

        // Si viene un vehículo preseleccionado (ej: desde la vista de detalle)
        $vehiculoSeleccionado = null;
        if ($request->filled('id_vehiculo')) {
            $vehiculoSeleccionado = Vehiculo::findOrFail($request->id_vehiculo);
        }

        return view('favoritos.create', compact('vehiculos', 'vehiculoSeleccionado'));
    }

    // Guardar nuevo favorito
    public function store(Request $request)
{
    $request->validate([
        'id_vehiculo' => ['required', 'exists:vehiculos,id_vehiculo'],
        'nota'        => ['nullable', 'string', 'max:255'],
    ]);

    // Verificar que no exista ya ese favorito para el usuario
    $existe = Favoritos::where('id_usuario', Auth::user()->id_usuario)
                      ->where('id_vehiculo', $request->id_vehiculo)
                      ->exists();

    if ($existe) {
        return redirect()->back()
            ->with('error', 'Este vehículo ya está en tus favoritos.')
            ->withInput();
    }

    try {
        Favoritos::create([
            'id_usuario'     => Auth::user()->id_usuario,
            'id_vehiculo'    => $request->id_vehiculo,
            'fecha_agregado' => now(),
            'estado'         => true,
            'nota'           => $request->nota,
        ]);

        return redirect()->route('favoritos.index')
            ->with('success', 'Vehículo agregado a favoritos correctamente.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error al agregar a favoritos. Intentá de nuevo.')
            ->withInput();
    }
}

    // Formulario de edición (para modificar la nota o el estado)
    public function edit(string $id_favorito)
    {
        $favorito = Favoritos::findOrFail($id_favorito);

        // Solo el dueño o un admin puede editar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $favorito->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('favoritos.index')
                ->with('error', 'No tenés permisos para editar este favorito.');
        }

        return view('favoritos.edit', compact('favorito'));
    }

    // Actualizar favorito
    public function update(Request $request, string $id_favorito)
    {
        $favorito = Favoritos::findOrFail($id_favorito);

        // Solo el dueño o un admin puede actualizar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $favorito->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('favoritos.index')
                ->with('error', 'No tenés permisos para editar este favorito.');
        }

        $request->validate([
            'estado' => ['required', 'in:0,1'],
            'nota'   => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $favorito->update([
                'estado' => $request->estado,
                'nota'   => $request->nota,
            ]);

            return redirect()->route('favoritos.index')
                ->with('success', 'Favorito actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el favorito. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Eliminar favorito
    public function destroy(string $id_favorito)
    {
        $favorito = Favoritos::findOrFail($id_favorito);

        // Solo el dueño o un admin puede eliminar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $favorito->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('favoritos.index')
                ->with('error', 'No tenés permisos para eliminar este favorito.');
        }

        try {
            $favorito->delete();

            return redirect()->route('favoritos.index')
                ->with('success', 'Favorito eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el favorito. Intentá de nuevo.');
        }
    }
}