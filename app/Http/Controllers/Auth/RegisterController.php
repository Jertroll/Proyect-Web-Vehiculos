<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validaciones del formulario de registro.
     * Cada campo del enunciado tiene su regla correspondiente.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre'       => ['required', 'string', 'max:100'],
            'email'        => ['required', 'string', 'email', 'max:100', 'unique:usuarios'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'telefono'     => ['nullable', 'string', 'max:20'],
            'tipo_usuario' => ['required', 'in:cliente,vendedor,admin'],
        ]);
    }

    protected function create(array $data)
    {
        return Usuario::create([
            'nombre'          => $data['nombre'],
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'telefono'        => $data['telefono'] ?? null,
            'fecha_registro'  => now(),
            'tipo_usuario'    => $data['tipo_usuario'],
        ]);
    }
}