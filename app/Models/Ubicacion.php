<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table      = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';
    public    $timestamps = false;

    protected $fillable = [
        'ciudad',
        'pais',
        'direccion',
        'latitud',
        'longitud',
        'codigo_postal',
    ];

    protected function casts(): array
    {
        return [
            'latitud'  => 'decimal:6',
            'longitud' => 'decimal:6',
        ];
    }

    // Relación con vehículos
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_ubicacion', 'id_ubicacion');
    }
}