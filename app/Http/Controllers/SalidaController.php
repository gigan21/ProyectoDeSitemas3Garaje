<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\Entrada;
use Illuminate\Http\Request;

class SalidaController extends Controller
{
    public function index()
    {
        $salidas = Salida::with('entrada.vehiculo')
            ->orderBy('fecha_salida', 'desc')
            ->paginate(10);
            
        return view('salidas.index', compact('salidas'));
    }

    public function create()
    {
        $entradas = Entrada::whereDoesntHave('salida')
            ->with('vehiculo')
            ->get();
            
        return view('salidas.create', compact('entradas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entrada_id' => 'required|exists:entradas,id',
            'total_pagado' => 'required|numeric|min:0'
        ]);

        // Obtener la entrada relacionada
        $entrada = Entrada::find($request->entrada_id);
        
        // Liberar el espacio
        $entrada->espacio->update(['estado' => 'libre']);

        // Registrar la salida
        Salida::create([
            'entrada_id' => $request->entrada_id,
            'fecha_salida' => now(),
            'total_pagado' => $request->total_pagado
        ]);

        return redirect()->route('salidas.index')
            ->with('success', 'Salida registrada exitosamente');
    }
    public function getFechaSalidaFormateadaAttribute()
{
    return $this->fecha_salida instanceof \Carbon\Carbon 
        ? $this->fecha_salida->format('d/m/Y H:i')
        : \Carbon\Carbon::parse($this->fecha_salida)->format('d/m/Y H:i');
}
}