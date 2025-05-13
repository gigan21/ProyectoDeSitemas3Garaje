<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMensual extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'monto',
        'fecha_pago'
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}