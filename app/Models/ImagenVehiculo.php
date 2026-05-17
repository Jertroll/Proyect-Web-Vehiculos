<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagenVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'imagenes_vehiculo';
    protected $primaryKey = 'id_imagen';
    public    $timestamps = false;

    protected $fillable = [
        'id_vehiculo',
        'url_imagen',
        'descripcion',
        'orden',
        'fecha_subida',
    ];

    protected function casts(): array
    {
        return [
            'fecha_subida' => 'datetime',
            'orden'        => 'integer',
        ];
    }

    // Relación con el vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }
}