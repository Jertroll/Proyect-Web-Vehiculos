<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Pago extends Model
{
 
    public $timestamps = false;

    protected $primaryKey = 'id_pago';

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