<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table      = 'vehiculos';
    protected $primaryKey = 'id_vehiculo';
    public    $timestamps = false;

    protected $fillable = [
        'marca',
        'modelo',
        'anio',
        'precio',
        'descripcion',
        'id_vendedor',
        'id_ubicacion',
        'estado',
        'fecha_publicacion',
    ];

    protected function casts(): array
    {
        return [
            'precio'            => 'decimal:2',
            'fecha_publicacion' => 'datetime',
        ];
    }

    // Relación con el vendedor (usuario)
    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'id_vendedor', 'id_usuario');
    }

    // Relación con la ubicación
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    // Relación con las imágenes
    public function imagenes()
    {
        return $this->hasMany(ImagenVehiculo::class, 'id_vehiculo', 'id_vehiculo')
                    ->orderBy('orden');
    }
    // Relación con las reseñas
    public function resenas()
    {
        return $this->hasMany(Resena::class, 'id_vehiculo', 'id_vehiculo');
    } 

    // Relación con las compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function favoritos()
    {
       return $this->hasMany(\App\Models\Favoritos::class, 'id_vehiculo', 'id_vehiculo');
    }

    // Método para marcar como vendido (usado por Kim en CompraController)
    public function marcarComoVendido()
    {
        $this->estado = 'vendido';
        $this->save();
    }

    // Método para marcar como disponible (usado si se cancela una compra)
    public function marcarComoDisponible()
    {
        $this->estado = 'disponible';
        $this->save();
    }
}