<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    // Apuntar a la tabla correcta
    protected $table = 'usuarios';

    // Llave primaria personalizada
    protected $primaryKey = 'id_usuario';

    // Desactivar timestamps automáticos (created_at / updated_at)
    // porque usamos fecha_registro manualmente
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
        'fecha_registro',
        'tipo_usuario',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'fecha_registro' => 'datetime',
        ];
    }
}