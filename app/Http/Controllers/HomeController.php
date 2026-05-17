<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehiculo;
use App\Models\Usuario;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();
        $datos   = [];

        if ($usuario->tipo_usuario === 'admin') {

            $datos['total_usuarios']        = Usuario::count();
            $datos['total_vehiculos']       = Vehiculo::count();
            $datos['vehiculos_disponibles'] = Vehiculo::where('estado', 'disponible')->count();
            $datos['vehiculos_vendidos']    = Vehiculo::where('estado', 'vendido')->count();
            // total_compras lo agrega Kim cuando cree el modelo Compra
            $datos['total_compras']         = 0;

        } elseif ($usuario->tipo_usuario === 'vendedor') {

            $datos['mis_vehiculos'] = Vehiculo::where('id_vendedor', $usuario->id_usuario)->count();
            $datos['vendidos']      = Vehiculo::where('id_vendedor', $usuario->id_usuario)
                                              ->where('estado', 'vendido')->count();
            $datos['disponibles']   = Vehiculo::where('id_vendedor', $usuario->id_usuario)
                                              ->where('estado', 'disponible')->count();

        } else {

            // Kim conecta estas queries cuando cree el modelo Compra
            $datos['mis_compras']        = 0;
            $datos['compras_pagas']      = 0;
            $datos['compras_pendientes'] = 0;

        }

        return view('home', compact('usuario', 'datos'));
    }
}