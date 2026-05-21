<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VueAuthController extends Controller
{
    // Mostrar formulario de login Vue
    public function showLogin()
    {
        // Si ya está autenticado en el guard vue, ir directo al módulo
        if (Auth::guard('vue')->check()) {
            return redirect()->route('vue.index');
        }

        return view('vue.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Usar el guard 'vue' - completamente independiente del guard 'web'
        if (Auth::guard('vue')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('vue.index');
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ]);
    }

    // Logout Vue - NO afecta la sesión del guard web (Fase 2)
    public function logout(Request $request)
    {
        Auth::guard('vue')->logout();

        
        $request->session()->regenerateToken();

        return redirect()->route('vue.login');
    }

    // Vista principal del módulo Vue 
    public function index()
    {
        return view('vue.index');
    }
}