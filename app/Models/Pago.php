<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pago
 *
 * @property $id_pago
 * @property $id_compra
 * @property $metodo_pago
 * @property $monto
 * @property $fecha_pago
 * @property $estado
 *
 * @property Compras $compras
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Pago extends Model
{
 
    public $timestamps = false;

    protected $primaryKey = 'id_pago';
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

    protected $fillable = [
        'id_compra',
        'metodo_pago',
        'monto',
        'fecha_pago',
        'estado'
    ];

    public function compra()
    {
        return $this->belongsTo(\App\Models\Compra::class, 'id_compra', 'id_compra');
    }
}