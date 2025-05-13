<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_espacio',
        'estado',
        'cliente_id'
    ];

    // Relación con Cliente (ahora genérica)
public function cliente()
{
    return $this->belongsTo(Cliente::class, 'cliente_id');
}

    // Relación con Entradas
    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }
   
public function entradaActiva()
{
    return $this->entradas()
        ->whereDoesntHave('salida')
        ->first();
}

public function estaOcupado()
{
    return $this->estado === 'ocupado' && $this->entradaActiva();
}
}