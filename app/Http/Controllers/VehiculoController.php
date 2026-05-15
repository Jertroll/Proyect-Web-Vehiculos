<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehiculoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar vehículos con filtros
    public function index(Request $request)
    {
        $query = Vehiculo::with(['vendedor', 'ubicacion', 'imagenes']);

        // Filtro por marca
        if ($request->filled('marca')) {
            $query->where('marca', 'like', '%' . $request->marca . '%');
        }

        // Filtro por año
        if ($request->filled('anio')) {
            $query->where('anio', $request->anio);
        }

        // Filtro por precio máximo
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $vehiculos = $query->orderBy('fecha_publicacion', 'desc')->get();

        return view('vehiculos.index', compact('vehiculos'));
    }

    // Ver detalle de un vehículo
    public function show(string $id)
    {
        $vehiculo = Vehiculo::with(['vendedor', 'ubicacion', 'imagenes'])
                            ->findOrFail($id);

        return view('vehiculos.show', compact('vehiculo'));
    }

    // Formulario de creación
    public function create()
    {
        $ubicaciones = Ubicacion::orderBy('ciudad')->get();
        return view('vehiculos.create', compact('ubicaciones'));
    }

    // Guardar nuevo vehículo
    public function store(Request $request)
    {
        $request->validate([
            'marca'        => ['required', 'string', 'max:50'],
            'modelo'       => ['required', 'string', 'max:50'],
            'anio'         => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'precio'       => ['required', 'numeric', 'min:0'],
            'descripcion'  => ['nullable', 'string'],
            'id_ubicacion' => ['nullable', 'exists:ubicaciones,id_ubicacion'],
        ]);

        try {
            Vehiculo::create([
                'marca'             => $request->marca,
                'modelo'            => $request->modelo,
                'anio'              => $request->anio,
                'precio'            => $request->precio,
                'descripcion'       => $request->descripcion,
                'id_vendedor'       => Auth::user()->id_usuario,
                'id_ubicacion'      => $request->id_ubicacion,
                'estado'            => 'disponible',
                'fecha_publicacion' => now(),
            ]);

            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo publicado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al publicar el vehículo. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Formulario de edición
    public function edit(string $id)
    {
        $vehiculo    = Vehiculo::findOrFail($id);
        $ubicaciones = Ubicacion::orderBy('ciudad')->get();

        // Solo el vendedor dueño o un admin puede editar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $vehiculo->id_vendedor !== Auth::user()->id_usuario) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'No tenés permisos para editar este vehículo.');
        }

        return view('vehiculos.edit', compact('vehiculo', 'ubicaciones'));
    }

    // Actualizar vehículo
    public function update(Request $request, string $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        // Solo el vendedor dueño o un admin puede actualizar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $vehiculo->id_vendedor !== Auth::user()->id_usuario) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'No tenés permisos para editar este vehículo.');
        }

        $request->validate([
            'marca'        => ['required', 'string', 'max:50'],
            'modelo'       => ['required', 'string', 'max:50'],
            'anio'         => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'precio'       => ['required', 'numeric', 'min:0'],
            'descripcion'  => ['nullable', 'string'],
            'estado'       => ['required', 'in:disponible,vendido'],
            'id_ubicacion' => ['nullable', 'exists:ubicaciones,id_ubicacion'],
        ]);

        try {
            $vehiculo->update([
                'marca'        => $request->marca,
                'modelo'       => $request->modelo,
                'anio'         => $request->anio,
                'precio'       => $request->precio,
                'descripcion'  => $request->descripcion,
                'estado'       => $request->estado,
                'id_ubicacion' => $request->id_ubicacion,
            ]);

            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el vehículo. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Eliminar vehículo
    public function destroy(string $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        // Solo el vendedor dueño o un admin puede eliminar
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $vehiculo->id_vendedor !== Auth::user()->id_usuario) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'No tenés permisos para eliminar este vehículo.');
        }

        // No permitir eliminar un vehículo vendido
        if ($vehiculo->estado === 'vendido') {
            return redirect()->route('vehiculos.index')
                ->with('error', 'No se puede eliminar un vehículo que ya fue vendido.');
        }

        try {
            $vehiculo->delete();

            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el vehículo. Intentá de nuevo.');
        }
    }
}