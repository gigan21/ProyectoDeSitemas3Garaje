<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'placa',
        'modelo',
        'color',
        'cliente_id'
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con Entradas
    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }

    // Verifica si el vehículo está actualmente dentro (entrada sin salida)
    public function tieneEntradaActiva(): bool
    {
        return $this->entradas()
            ->whereDoesntHave('salida')
            ->exists();
    }
}