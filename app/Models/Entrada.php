<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehiculo_id',
        'espacio_id',
        'fecha_entrada'
    ];
    protected $casts = [
        'fecha_entrada' => 'datetime' // Conversión automática a Carbon
    ];
    // Relación con Vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    // Relación con Espacio
    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }

    // Relación con Salida (uno a uno)
    public function salida()
    {
        return $this->hasOne(Salida::class);
    }
    
public function estaActiva()
{
    return $this->salida === null;
}
// Accesor para fecha formateada (igual que en Salida)
public function getFechaEntradaFormateadaAttribute()
{
    return $this->fecha_entrada 
        ? $this->fecha_entrada->format('d/m/Y H:i')
        : 'N/A';
}
}