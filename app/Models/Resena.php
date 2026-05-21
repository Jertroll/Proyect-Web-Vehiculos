<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{

    public $timestamps = false;

    protected $primaryKey = 'id_resena';

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