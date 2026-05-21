<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehiculo;
use App\Models\Usuario;
use App\Models\Compra;

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

            $datos['total_usuarios'] = Usuario::count();
            $datos['total_vehiculos'] = Vehiculo::count();
            $datos['vehiculos_disponibles'] = Vehiculo::where('estado', 'disponible')->count();
            $datos['vehiculos_vendidos'] = Vehiculo::where('estado', 'vendido')->count();
            $datos['total_compras'] = Compra::count();

        } elseif ($usuario->tipo_usuario === 'vendedor') {

            $datos['mis_vehiculos'] = Vehiculo::where('id_vendedor', $usuario->id_usuario)->count();
            $datos['vendidos']      = Vehiculo::where('id_vendedor', $usuario->id_usuario)->where('estado', 'vendido')->count();
            $datos['disponibles']   = Vehiculo::where('id_vendedor', $usuario->id_usuario)->where('estado', 'disponible')->count();

        } else {

            $datos['mis_compras'] = Compra::where('id_usuario', $usuario->id_usuario)->count();
            $datos['compras_pagas'] = Compra::where('id_usuario', $usuario->id_usuario)->where('estado', 'pagado')->count();
            $datos['compras_pendientes'] = Compra::where('id_usuario', $usuario->id_usuario) ->where('estado', 'pendiente')->count();

        }

        return view('home.home', compact('usuario', 'datos'));
    }
}