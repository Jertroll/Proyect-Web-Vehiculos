<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'id_compra';

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