<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $query = Compra::with([
          'usuario',
          'vehiculo.vendedor',
          'vehiculo.ubicacion',
          'pagos',
          'vehiculo.resenas',
          'vehiculo.favoritos'
        ]);

        if (Auth::user()->tipo_usuario === 'cliente') {
            $query->where('id_usuario', Auth::user()->id_usuario);
        }elseif (Auth::user()->tipo_usuario === 'vendedor'){
            $query->whereHas('vehiculo', function ($q) {
                $q->where('id_vendedor', Auth::user()->id_usuario);
            });
        }

        $compras = $query->orderBy('fecha_compra', 'desc')->get();

         return view('historial.index', compact('compras'));
    }
}