<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Salida;
use Illuminate\Http\Request;
use PDF;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function generar(Request $request)
    {
        $tipo = $request->tipo_reporte;
        
        switch($tipo) {
            case 'ingresos':
                return $this->generarReporteIngresos($request);
            case 'clientes':
                return $this->generarReporteClientes($request);
            default:
                return back()->with('error', 'Tipo de reporte no válido');
        }
    }

    private function generarReporteIngresos($request)
{
    $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
    ]);

    $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio);
    $fechaFin = \Carbon\Carbon::parse($request->fecha_fin);

    $salidas = Salida::with([
            'entrada' => function($query) {
                $query->with(['vehiculo']);
            }
        ])
        ->whereBetween('fecha_salida', [$fechaInicio, $fechaFin])
        ->get();
        
    $total = $salidas->sum('total_pagado');
    
    $pdf = PDF::loadView('reportes.ingresos', [
        'salidas' => $salidas,
        'total' => $total,
        'fecha_inicio' => $fechaInicio,
        'fecha_fin' => $fechaFin
    ])->setPaper('a4', 'landscape');
    
    return $pdf->download('reporte_ingresos_'.now()->format('YmdHis').'.pdf');
}

private function generarReporteClientes($request)
{
    // Medición del tiempo (solo para diagnóstico)
    $start = microtime(true);
    
    $query = Cliente::query()->with('vehiculos');
    
    if($request->tipo_cliente && $request->tipo_cliente != 'todos') {
        $query->where('tipo_cliente', $request->tipo_cliente);
    }
    
    $clientes = $query->orderBy('nombre')->get();
    
    // Verifica cuántos clientes hay realmente
    logger('Clientes encontrados: '.$clientes->count());
    logger('Tiempo consulta: '.(microtime(true) - $start).' segundos');
    
    $pdf = PDF::loadView('reportes.clientes', [
            'clientes' => $clientes,
            'tipo' => $request->tipo_cliente ?? 'todos'
        ])
        ->setPaper('a4', 'portrait');
    
    logger('Tiempo total: '.(microtime(true) - $start).' segundos');
    
    return $pdf->download('reporte_clientes.pdf');
}
}