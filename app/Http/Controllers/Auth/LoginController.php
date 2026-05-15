<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirigir al home después del login
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Sobrescribimos el campo de login
     * Por defecto Laravel usa 'email', como nuestra
     * tabla también usa email no hace falta cambiarlo,
     * pero lo dejamos explícito por claridad
     */
    public function username()
    {
        return 'email';
    }
}