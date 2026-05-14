<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Compra
 *
 * @property $id_compra
 * @property $id_usuario
 * @property $id_vehiculo
 * @property $precio_final
 * @property $fecha_compra
 * @property $estado
 *
 * @property Usuario $usuario
 * @property Vehiculo $vehiculo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Compra extends Model
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
        'precio_final',
        'fecha_compra',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vehiculo()
    {
        return $this->belongsTo(\App\Models\Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class, 'id_compra', 'id_compra');
    }
}