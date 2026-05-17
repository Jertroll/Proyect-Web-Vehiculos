<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UbicacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Solo admin puede gestionar ubicaciones
        $this->middleware(function ($request, $next) {
            if (Auth::user()->tipo_usuario !== 'admin') {
                return redirect()->route('home')
                    ->with('error', 'No tenés permisos para acceder a esta sección.');
            }
            return $next($request);
        });
    }

    // Listar todas las ubicaciones
    public function index()
    {
        $ubicaciones = Ubicacion::withCount('vehiculos')
                                ->orderBy('pais')
                                ->orderBy('ciudad')
                                ->get();

        return view('ubicaciones.index', compact('ubicaciones'));
    }

    // Formulario de creación
    public function create()
    {
        return view('ubicaciones.create');
    }

    // Guardar nueva ubicación
    public function store(Request $request)
    {
        $request->validate([
            'ciudad'        => ['required', 'string', 'max:100'],
            'pais'          => ['required', 'string', 'max:100'],
            'direccion'     => ['nullable', 'string', 'max:255'],
            'latitud'       => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'      => ['nullable', 'numeric', 'between:-180,180'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
        ]);

        try {
            Ubicacion::create([
                'ciudad'        => $request->ciudad,
                'pais'          => $request->pais,
                'direccion'     => $request->direccion,
                'latitud'       => $request->latitud,
                'longitud'      => $request->longitud,
                'codigo_postal' => $request->codigo_postal,
            ]);

            return redirect()->route('ubicaciones.index')
                ->with('success', 'Ubicación creada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear la ubicación. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Formulario de edición
    public function edit(string $id)
    {
        $ubicacion = Ubicacion::findOrFail($id);
        return view('ubicaciones.edit', compact('ubicacion'));
    }

    // Actualizar ubicación
    public function update(Request $request, string $id)
    {
        $ubicacion = Ubicacion::findOrFail($id);

        $request->validate([
            'ciudad'        => ['required', 'string', 'max:100'],
            'pais'          => ['required', 'string', 'max:100'],
            'direccion'     => ['nullable', 'string', 'max:255'],
            'latitud'       => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'      => ['nullable', 'numeric', 'between:-180,180'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
        ]);

        try {
            $ubicacion->update([
                'ciudad'        => $request->ciudad,
                'pais'          => $request->pais,
                'direccion'     => $request->direccion,
                'latitud'       => $request->latitud,
                'longitud'      => $request->longitud,
                'codigo_postal' => $request->codigo_postal,
            ]);

            return redirect()->route('ubicaciones.index')
                ->with('success', 'Ubicación actualizada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la ubicación. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Eliminar ubicación
    public function destroy(string $id)
    {
        $ubicacion = Ubicacion::withCount('vehiculos')->findOrFail($id);

        // No permitir eliminar si tiene vehículos asociados
        if ($ubicacion->vehiculos_count > 0) {
            return redirect()->route('ubicaciones.index')
                ->with('error', 'No se puede eliminar esta ubicación porque tiene ' .
                                $ubicacion->vehiculos_count . ' vehículo(s) asociado(s).');
        }

        try {
            $ubicacion->delete();

            return redirect()->route('ubicaciones.index')
                ->with('success', 'Ubicación eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la ubicación. Intentá de nuevo.');
        }
    }
}