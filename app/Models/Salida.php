<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salida extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrada_id',
        'fecha_salida',
        'total_pagado',
        'es_gratis',
        
    ];

    protected $casts = [
        'fecha_salida' => 'datetime',
        'total_pagado' => 'decimal:2',
        'es_gratis' => 'boolean'
    ];

    // Relación con Entrada
    public function entrada(): BelongsTo
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

    public function pagoMensual()
    {
        return $this->hasOne(PagoMensual::class);
    }

    // Nuevo método para verificar si es cliente mensual
    public function esMensual()
    {
        return $this->entrada && 
               $this->entrada->vehiculo && 
               $this->entrada->vehiculo->cliente && 
               $this->entrada->vehiculo->cliente->tipo_cliente === 'mensual';
    }
    
    // Nuevo: Método para obtener el tipo de cliente
    public function getTipoClienteAttribute()
    {
        return $this->esMensual() ? 'Mensual' : 'Ocasional';
    }
}

