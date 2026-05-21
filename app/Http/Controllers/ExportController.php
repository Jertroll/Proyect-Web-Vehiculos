<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Vehiculo;
use App\Models\ImagenVehiculo;
use App\Models\Ubicacion;
use App\Models\Compra;
use App\Models\Pago;
use App\Models\Favoritos;
use App\Models\Resena;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.vue');
    }

    public function usuarios()
    {
        try {
            return response()->json(Usuario::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    public function vehiculos()
    {
        try {
            return response()->json(Vehiculo::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener vehículos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function imagenesVehiculo()
    {
        try {
            return response()->json(ImagenVehiculo::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener imágenes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ubicaciones()
    {
        try {
            return response()->json(Ubicacion::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener ubicaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    public function compras()
    {
        try {
            return response()->json(Compra::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener compras: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pagos()
    {
        try {
            return response()->json(Pago::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener pagos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function favoritos()
    {
        try {
            return response()->json(Favoritos::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener favoritos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resenas()
    {
        try {
            return response()->json(Resena::all());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener reseñas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function abrirExplorador()
{
    try {
        // 1. Detectar el sistema operativo
        $os = strtoupper(substr(PHP_OS, 0, 3));

        if ($os === 'WIN') {
            // Lógica para Windows
            $carpeta = getenv('USERPROFILE') . '\\Downloads';
            exec('explorer.exe "' . $carpeta . '"');
        } else {
            // Lógica para Linux (Mint, Ubuntu, etc.)
            // En Linux, getenv('HOME') nos da algo como /home/jertroll
            $carpeta = getenv('HOME') . '/Descargas'; 
            
            // Si el sistema está en inglés, la carpeta podría llamarse 'Downloads'
            if (!file_exists($carpeta)) {
                $carpeta = getenv('HOME') . '/Downloads';
            }

            // 'xdg-open' es el comando universal en Linux para abrir carpetas
            // con el explorador por defecto del sistema (en Mint abrirá Nemo)
            exec('xdg-open "' . $carpeta . '" > /dev/null 2>&1 &');
        }

        return response()->json([
            'mensaje' => 'Explorador abierto correctamente.',
            'carpeta' => $carpeta
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al abrir el explorador: ' . $e->getMessage()
        ], 500);
    }
}
}