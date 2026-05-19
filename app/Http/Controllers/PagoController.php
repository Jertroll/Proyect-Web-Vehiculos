<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar pagos
    public function index(Request $request)
    {
        $query = Pago::with(['compra']);

        // Un usuario normal solo ve los pagos de sus propias compras
        if (Auth::user()->tipo_usuario !== 'admin') {
            $query->whereHas('compra', function ($q) {
                $q->where('id_usuario', Auth::user()->id_usuario);
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por método de pago
        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->get();

        return view('pagos.index', compact('pagos'));
    }

    // Ver detalle de un pago
    public function show(string $id_pago)
    {
        $pago = Pago::with(['compra'])->findOrFail($id_pago);

        // Solo el dueño de la compra o un admin puede ver el detalle
        if (Auth::user()->tipo_usuario !== 'admin' &&
            $pago->compra->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('pagos.index')
                ->with('error', 'No tenés permisos para ver este pago.');
        }

        return view('pagos.show', compact('pago'));
    }

    // Formulario de creación
    public function create(Request $request)
    {
        // Solo mostrar las compras pendientes del usuario autenticado
        $compras = Compra::where('id_usuario', Auth::user()->id_usuario)
                         ->where('estado', 'pendiente')
                         ->get();

        $compraSeleccionada = null;
        if ($request->filled('id_compra')) {
            $compraSeleccionada = Compra::findOrFail($request->id_compra);
        }

        return view('pagos.create', compact('compras', 'compraSeleccionada'));
    }

    // Guardar nuevo pago
    public function store(Request $request)
    {
        $request->validate([
            'id_compra'   => ['required', 'exists:compras,id_compra'],
            'metodo_pago' => ['required', 'string', 'max:50'],
            'monto'       => ['required', 'numeric', 'min:0.01'],
        ]);

        $compra = Compra::findOrFail($request->id_compra);

        // Verificar que la compra pertenece al usuario
        if ($compra->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->back()
                ->with('error', 'No tenés permisos para pagar esta compra.')
                ->withInput();
        }

        // Verificar que la compra esté pendiente
        if ($compra->estado !== 'pendiente') {
            return redirect()->back()
                ->with('error', 'Esta compra no está pendiente de pago.')
                ->withInput();
        }

        try {
            Pago::create([
                'id_compra'   => $compra->id_compra,
                'metodo_pago' => $request->metodo_pago,
                'monto'       => $request->monto,
                'fecha_pago'  => now(),
                'estado'      => 'pendiente',
            ]);

            return redirect()->route('pagos.index')
                ->with('success', 'Pago registrado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar el pago. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Formulario de edición
    public function edit(string $id_pago)
    {
        $pago = Pago::findOrFail($id_pago);

        // Solo un admin puede editar pagos
        if (Auth::user()->tipo_usuario !== 'admin') {
            return redirect()->route('pagos.index')
                ->with('error', 'No tenés permisos para editar este pago.');
        }

        return view('pagos.edit', compact('pago'));
    }

    // Actualizar pago
    public function update(Request $request, string $id_pago)
{
    $pago = Pago::findOrFail($id_pago);

    // Solo un admin puede actualizar pagos
    if (Auth::user()->tipo_usuario !== 'admin' &&
        $pago->compra->id_usuario !== Auth::user()->id_usuario) {
        return redirect()->route('pagos.index')
            ->with('error', 'No tenés permisos para editar este pago.');
    }

    $request->validate([
        'metodo_pago' => ['required', 'string', 'max:50'],
        'monto'       => [
            'required',
            'numeric',
            'min:0.01'
        ],
        'fecha_pago'  => ['required', 'date'],
        'estado'      => ['required', 'in:pendiente,completado,rechazado'],
    ]);

    try {
        $pago->update([
            'metodo_pago' => $request->metodo_pago,
            'monto'       => $request->monto,
            'fecha_pago'  => $request->fecha_pago,
            'estado'      => $request->estado,
        ]);

        // Si el pago se completa, marcar la compra como pagada y el vehículo como vendido
        if ($request->estado === 'completado') {
            $pago->compra->update(['estado' => 'pagado']);
            $pago->compra->vehiculo->update(['estado' => 'vendido']);
        }

        // Si el pago se rechaza, marcar la compra como cancelada y liberar el vehículo
        if ($request->estado === 'rechazado') {
            $pago->compra->update(['estado' => 'cancelado']);
            $pago->compra->vehiculo->update(['estado' => 'disponible']);
        }

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado correctamente.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error al actualizar el pago. Intentá de nuevo.')
            ->withInput();
    }
}

    // Eliminar pago
    public function destroy(string $id_pago)
    {
        $pago = Pago::findOrFail($id_pago);

        // Solo un admin puede eliminar pagos
        if (Auth::user()->tipo_usuario !== 'admin') {
            return redirect()->route('pagos.index')
                ->with('error', 'No tenés permisos para eliminar este pago.');
        }

        // No permitir eliminar un pago completado
        if ($pago->estado === 'completado') {
            return redirect()->route('pagos.index')
                ->with('error', 'No se puede eliminar un pago que ya fue completado.');
        }

        try {
            $pago->delete();

            return redirect()->route('pagos.index')
                ->with('success', 'Pago eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el pago. Intentá de nuevo.');
        }
    }
}