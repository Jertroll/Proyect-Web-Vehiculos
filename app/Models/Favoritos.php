<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Favoritos
 *
 * @property $id_favorito
 * @property $id_usuario
 * @property $id_vehiculo
 * @property $fecha_agregado
 * @property $estado
 * @property $nota
 *
 * @property Usuario $usuario
 * @property Vehiculo $vehiculo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Favoritos extends Model
{

    public $timestamps = false;
    protected $primaryKey = 'id_favorito';
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

    protected $fillable = [
        'id_usuario',
        'id_vehiculo',
        'fecha_agregado',
        'estado',
        'nota'
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