<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMensual extends Model
{
    use HasFactory;
    protected $table = 'pagos_mensuales';
    protected $fillable = [
        'cliente_id',
        'monto',
        'fecha_pago'
    ];
    protected $casts = [
        'fecha_pago' => 'date', // Cambiado a 'date' para coincidir con tu migración DATE
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    // Relación con Cliente
    public function cliente()
    {
       return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}