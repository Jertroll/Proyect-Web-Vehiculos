<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar compras con filtros
    public function index(Request $request)
    {
        $query = Compra::with(['usuario', 'vehiculo']);

        // Un usuario normal solo ve sus propias compras
        if (Auth::user()->tipo_usuario !== 'admin') {
            $query->where('id_usuario', Auth::user()->id_usuario);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_compra', '>=', $request->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_compra', '<=', $request->fecha_hasta);
        }

        $compras = $query->orderBy('fecha_compra', 'desc')->get();

        return view('compras.index', compact('compras'));
    }

    // Ver detalle de una compra
    public function show(string $id_compra)
    {
        $compra = Compra::with(['usuario', 'vehiculo', 'pagos'])
                        ->findOrFail($id_compra);

        // Solo el comprador dueño o un admin puede ver el detalle
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $compra->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('compras.index')
                ->with('error', 'No tenés permisos para ver esta compra.');
        }

        return view('compras.show', compact('compra'));
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

        return view('compras.create', compact('vehiculos', 'vehiculoSeleccionado'));
    }

    // Guardar nueva compra
    public function store(Request $request)
    {
        $request->validate([
            'id_vehiculo' => ['required', 'exists:vehiculos,id_vehiculo'],
        ]);

        $vehiculo = Vehiculo::findOrFail($request->id_vehiculo);

        // Verificar que el vehículo esté disponible
        if ($vehiculo->estado !== 'disponible') {
            return redirect()->back()
                ->with('error', 'El vehículo ya no está disponible.')
                ->withInput();
        }

        // Un usuario no puede comprar su propio vehículo
        if ($vehiculo->id_vendedor === Auth::user()->id_usuario) {
            return redirect()->back()
                ->with('error', 'No podés comprar tu propio vehículo.')
                ->withInput();
        }

        try {
            Compra::create([
                'id_usuario'   => Auth::user()->id_usuario,
                'id_vehiculo'  => $vehiculo->id_vehiculo,
                'precio_final' => $vehiculo->precio,
                'fecha_compra' => now(),
                'estado'       => 'pendiente',
            ]);

            // Marcar el vehículo como vendido
            $vehiculo->update(['estado' => 'vendido']);

            return redirect()->route('compras.index')
                ->with('success', 'Compra registrada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar la compra. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Formulario de edición
    public function edit(string $id_compra)
    {
        $compra = Compra::findOrFail($id_compra);

        // Solo un admin puede editar compras
        if (Auth::user()->tipo_usuario !== 'admin') {
            return redirect()->route('compras.index')
                ->with('error', 'No tenés permisos para editar esta compra.');
        }

        return view('compras.edit', compact('compra'));
    }

    // Actualizar compra
    public function update(Request $request, string $id_compra)
    {
        $compra = Compra::findOrFail($id_compra);

        // Solo un admin puede actualizar compras
        if (Auth::user()->tipo_usuario !== 'admin') {
            return redirect()->route('compras.index')
                ->with('error', 'No tenés permisos para editar esta compra.');
        }

        $request->validate([
            'precio_final' => ['required', 'numeric', 'min:0'],
            'fecha_compra' => ['required', 'date'],
            'estado'       => ['required', 'in:pendiente,pagado,cancelado'],
        ]);

        try {
            $compra->update([
                'precio_final' => $request->precio_final,
                'fecha_compra' => $request->fecha_compra,
                'estado'       => $request->estado,
            ]);

            // Si la compra se cancela, volver a poner el vehículo como disponible
            if ($request->estado === 'cancelado') {
                $compra->vehiculo->update(['estado' => 'disponible']);
            }

            return redirect()->route('compras.index')
                ->with('success', 'Compra actualizada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la compra. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Eliminar compra
    public function destroy(string $id_compra)
    {
        $compra = Compra::findOrFail($id_compra);

        // Solo un admin puede eliminar compras
        if (Auth::user()->tipo_usuario !== 'admin') {
            return redirect()->route('compras.index')
                ->with('error', 'No tenés permisos para eliminar esta compra.');
        }

        // No permitir eliminar una compra pagada
        if ($compra->estado === 'pagado') {
            return redirect()->route('compras.index')
                ->with('error', 'No se puede eliminar una compra que ya fue pagada.');
        }

        try {
            // Si la compra no estaba pagada, liberar el vehículo
            if ($compra->estado !== 'pagado') {
                $compra->vehiculo->update(['estado' => 'disponible']);
            }

            $compra->delete();

            return redirect()->route('compras.index')
                ->with('success', 'Compra eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la compra. Intentá de nuevo.');
        }
    }
}