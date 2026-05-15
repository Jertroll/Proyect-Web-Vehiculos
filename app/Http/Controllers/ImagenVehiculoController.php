<?php

namespace App\Http\Controllers;

use App\Models\ImagenVehiculo;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImagenVehiculoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar imágenes de un vehículo específico
    public function index(Request $request)
    {
        // Si viene id_vehiculo en la URL filtramos por ese vehículo
        $query = ImagenVehiculo::with('vehiculo');

        if ($request->filled('id_vehiculo')) {
            $query->where('id_vehiculo', $request->id_vehiculo);
        }

        $imagenes  = $query->orderBy('id_vehiculo')->orderBy('orden')->get();
        $vehiculos = Vehiculo::orderBy('marca')->get();

        return view('imagenes.index', compact('imagenes', 'vehiculos'));
    }

    // Formulario de creación
    public function create(Request $request)
    {
        $vehiculos = Vehiculo::orderBy('marca')->get();

        // Si viene preseleccionado un vehículo desde la vista show
        $id_vehiculo = $request->query('id_vehiculo');

        return view('imagenes.create', compact('vehiculos', 'id_vehiculo'));
    }

    // Guardar nueva imagen
    public function store(Request $request)
    {
        $request->validate([
            'id_vehiculo' => ['required', 'exists:vehiculos,id_vehiculo'],
            'url_imagen'  => ['required', 'string', 'max:255', 'url'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'orden'       => ['required', 'integer', 'min:0'],
        ]);

        // Solo el vendedor dueño o admin puede agregar imágenes
        $vehiculo = Vehiculo::findOrFail($request->id_vehiculo);

        if (Auth::user()->tipo_usuario !== 'admin' &&
            $vehiculo->id_vendedor !== Auth::user()->id_usuario) {
            return redirect()->route('imagenes-vehiculo.index')
                ->with('error', 'No tenés permisos para agregar imágenes a este vehículo.');
        }

        try {
            ImagenVehiculo::create([
                'id_vehiculo' => $request->id_vehiculo,
                'url_imagen'  => $request->url_imagen,
                'descripcion' => $request->descripcion,
                'orden'       => $request->orden,
                'fecha_subida' => now(),
            ]);

            return redirect()->route('vehiculos.show', $request->id_vehiculo)
                ->with('success', 'Imagen agregada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al agregar la imagen. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Formulario de edición
    public function edit(string $id)
    {
        $imagen    = ImagenVehiculo::with('vehiculo')->findOrFail($id);
        $vehiculos = Vehiculo::orderBy('marca')->get();

        // Solo el vendedor dueño o admin puede editar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $imagen->vehiculo->id_vendedor !== Auth::user()->id_usuario) {
            return redirect()->route('imagenes-vehiculo.index')
                ->with('error', 'No tenés permisos para editar esta imagen.');
        }

        return view('imagenes.edit', compact('imagen', 'vehiculos'));
    }

    // Actualizar imagen
    public function update(Request $request, string $id)
    {
        $imagen = ImagenVehiculo::with('vehiculo')->findOrFail($id);

        // Solo el vendedor dueño o admin puede actualizar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $imagen->vehiculo->id_vendedor !== Auth::user()->id_usuario) {
            return redirect()->route('imagenes-vehiculo.index')
                ->with('error', 'No tenés permisos para editar esta imagen.');
        }

        $request->validate([
            'url_imagen'  => ['required', 'string', 'max:255', 'url'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'orden'       => ['required', 'integer', 'min:0'],
        ]);

        try {
            $imagen->update([
                'url_imagen'  => $request->url_imagen,
                'descripcion' => $request->descripcion,
                'orden'       => $request->orden,
            ]);

            return redirect()->route('vehiculos.show', $imagen->id_vehiculo)
                ->with('success', 'Imagen actualizada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la imagen. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Eliminar imagen
    public function destroy(string $id)
    {
        $imagen = ImagenVehiculo::with('vehiculo')->findOrFail($id);

        // Solo el vendedor dueño o admin puede eliminar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $imagen->vehiculo->id_vendedor !== Auth::user()->id_usuario) {
            return redirect()->route('imagenes-vehiculo.index')
                ->with('error', 'No tenés permisos para eliminar esta imagen.');
        }

        try {
            $id_vehiculo = $imagen->id_vehiculo;
            $imagen->delete();

            return redirect()->route('vehiculos.show', $id_vehiculo)
                ->with('success', 'Imagen eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la imagen. Intentá de nuevo.');
        }
    }
}