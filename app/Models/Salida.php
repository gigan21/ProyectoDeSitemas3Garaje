<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrada_id',
        'fecha_salida',
        'total_pagado'
    ];
    protected $casts = [
        'fecha_salida' => 'datetime'
    ];
    // Relación con Entrada
    public function entrada()
    {
        return $this->belongsTo(Entrada::class);
    }
    public function getFechaSalidaFormateadaAttribute()
    {
        return $this->fecha_salida 
            ? $this->fecha_salida->format('d/m/Y H:i')
            : 'N/A';
    }

    // Nuevo: Calcular tiempo estacionado dinámicamente
    public function getTiempoEstacionadoAttribute()
    {
        if ($this->fecha_salida && $this->entrada && $this->entrada->fecha_entrada) {
            return $this->entrada->fecha_entrada->diffForHumans($this->fecha_salida, true);
            // O para formato más preciso:
            // return $this->entrada->fecha_entrada->diff($this->fecha_salida)->format('%d días %h horas %i minutos');
        }
        return 'N/A';
    }
}

