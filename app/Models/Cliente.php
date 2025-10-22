<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'tipo_cliente'
    ];

    // Relación con Vehículos
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }

    // Relación con Espacios (como cliente mensual)
    public function espacios()
    {
        return $this->hasMany(Espacio::class, 'cliente_id');
    }

    // Relación con Pagos Mensuales
    public function pagosMensuales()
    {
        return $this->hasMany(PagoMensual::class);
    }

    // Verifica si el cliente es mensual
    public function esMensual(): bool
    {
        return $this->tipo_cliente === 'mensual';
    }

    // Verifica si el cliente tiene asignación activa en el parqueo
    public function tieneAsignacionActiva(): bool
    {
        // 1) Algún vehículo del cliente tiene una entrada sin salida
        $vehiculoConEntradaActiva = $this->vehiculos()
            ->whereHas('entradas', function ($q) {
                $q->whereDoesntHave('salida');
            })
            ->exists();

        if ($vehiculoConEntradaActiva) {
            return true;
        }

        // 2) Algún espacio asociado al cliente (mensual) tiene entrada activa
        $espacioConEntradaActiva = $this->espacios()
            ->whereHas('entradas', function ($q) {
                $q->whereDoesntHave('salida');
            })
            ->exists();

        return $espacioConEntradaActiva;
    }
}