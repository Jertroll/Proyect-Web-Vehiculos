<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthVue
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar sesión del guard 'vue', NO del guard 'web'
        if (!Auth::guard('vue')->check()) {
            return redirect()->route('vue.login')
                ->with('error', 'Debes iniciar sesión en el módulo Vue para acceder.');
        }

        return $next($request);
    }
}