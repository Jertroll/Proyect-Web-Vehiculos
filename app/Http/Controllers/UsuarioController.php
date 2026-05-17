<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Solo admin puede gestionar usuarios
        $this->middleware(function ($request, $next) {
            if (Auth::user()->tipo_usuario !== 'admin') {
                return redirect()->route('home')
                    ->with('error', 'No tenés permisos para acceder a esta sección.');
            }
            return $next($request);
        });
    }

    // Listar todos los usuarios
    public function index()
    {
        $usuarios = Usuario::orderBy('fecha_registro', 'desc')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    // Formulario de creación
    public function create()
    {
        return view('usuarios.create');
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'email'        => ['required', 'string', 'email', 'max:100', 'unique:usuarios'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'telefono'     => ['nullable', 'string', 'max:20'],
            'tipo_usuario' => ['required', 'in:cliente,vendedor,admin'],
        ]);

        try {
            Usuario::create([
                'nombre'         => $request->nombre,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'telefono'       => $request->telefono,
                'fecha_registro' => now(),
                'tipo_usuario'   => $request->tipo_usuario,
            ]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el usuario. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Formulario de edición
    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    // Actualizar usuario
    public function update(Request $request, string $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre'       => ['required', 'string', 'max:100'],
            'email'        => ['required', 'string', 'email', 'max:100', 'unique:usuarios,email,' . $id . ',id_usuario'],
            'telefono'     => ['nullable', 'string', 'max:20'],
            'tipo_usuario' => ['required', 'in:cliente,vendedor,admin'],
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $datos = [
                'nombre'       => $request->nombre,
                'email'        => $request->email,
                'telefono'     => $request->telefono,
                'tipo_usuario' => $request->tipo_usuario,
            ];

            // Solo actualizar password si se ingresó uno nuevo
            if ($request->filled('password')) {
                $datos['password'] = Hash::make($request->password);
            }

            $usuario->update($datos);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el usuario. Intentá de nuevo.')
                ->withInput();
        }
    }

    // Eliminar usuario
    public function destroy(string $id)
    {
        // No permitir eliminar el usuario logueado
        if (Auth::user()->id_usuario == $id) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No podés eliminar tu propio usuario.');
        }

        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el usuario. Intentá de nuevo.');
        }
    }
}