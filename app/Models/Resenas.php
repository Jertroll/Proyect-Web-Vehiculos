<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Resena
 *
 * @property $id_resena
 * @property $id_usuario
 * @property $id_vehiculo
 * @property $calificacion
 * @property $comentario
 * @property $fecha
 *
 * @property Usuario $usuario
 * @property Vehiculo $vehiculo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Resena extends Model
{

    public $timestamps = false;

    protected $perPage = 20;
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

    protected $fillable = [
        'id_usuario',
        'id_vehiculo',
        'calificacion',
        'comentario',
        'fecha'
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vehiculo()
    {
        return $this->belongsTo(\App\Models\Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }
}