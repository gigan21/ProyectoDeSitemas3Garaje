<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Salida;
use Illuminate\Http\Request;
use App\Models\Espacio;
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


private function generarReportePagosMensuales($request)
{
    $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
    ]);

    $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio);
    $fechaFin = \Carbon\Carbon::parse($request->fecha_fin);

    $pagosMensuales = PagoMensual::with(['cliente'])
        ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
        ->get();

    $total = $pagosMensuales->sum('monto');

    $pdf = PDF::loadView('reportes.pagos_mensuales', [
        'pagos' => $pagosMensuales,
        'total' => $total,
        'fecha_inicio' => $fechaInicio,
        'fecha_fin' => $fechaFin
    ])->setPaper('a4', 'portrait');

    return $pdf->download('reporte_pagos_mensuales_'.now()->format('YmdHis').'.pdf');
}
private function generarReporteIngresos($request)
{
    $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
    ]);

    $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio)->startOfDay();
    $fechaFin = \Carbon\Carbon::parse($request->fecha_fin)->endOfDay();

    // Cargar todas las relaciones necesarias
    $salidas = Salida::with([
            'entrada.vehiculo', // Carga la entrada y su vehículo asociado
            'entrada.espacio'   // Carga también el espacio si lo necesitas
        ])
        ->whereHas('entrada', function($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_entrada', [$fechaInicio, $fechaFin]);
        })
        ->orWhereBetween('fecha_salida', [$fechaInicio, $fechaFin])
        ->orderBy('fecha_salida', 'desc')
        ->get();

    // Calcular el total
    $total = $salidas->sum('total_pagado');

    // Verificar datos (para depuración)
    logger('Salidas encontradas: '.$salidas->count());
    foreach($salidas as $salida) {
        logger('Salida ID: '.$salida->id.' - Total: '.$salida->total_pagado);
    }

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
public function generarReporteEspacios(Request $request)
{
    $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ]);

    // Obtener todos los espacios
    $totalEspacios = Espacio::count();
    
    // Obtener espacios ocupados y libres
    $espaciosOcupados = Espacio::where('estado', 'ocupado')->count();
    $espaciosLibres = Espacio::where('estado', 'libre')->count();

    // Calcular el porcentaje de uso
    $porcentajeUso = $totalEspacios > 0 ? ($espaciosOcupados / $totalEspacios) * 100 : 0;

    // Obtener la utilización de cada espacio
    $utilizacionEspacios = Espacio::withCount(['entradas' => function ($query) use ($request) {
        $query->whereBetween('fecha_entrada', [$request->fecha_inicio, $request->fecha_fin]);
    }])->get();

    // Calcular el espacio más utilizado
    $espacioMasUtilizado = $utilizacionEspacios->sortByDesc('entradas_count')->first();

    // Obtener todos los espacios para la tabla
    $espacios = Espacio::all(); // Asegúrate de que esta línea esté presente

    return view('reportes.reportes_espacios', [
        'totalEspacios' => $totalEspacios,
        'espaciosOcupados' => $espaciosOcupados,
        'espaciosLibres' => $espaciosLibres,
        'porcentajeUso' => $porcentajeUso,
        'espacioMasUtilizado' => $espacioMasUtilizado,
        'espacios' => $espacios, // Asegúrate de pasar la variable a la vista
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
    ]);
}

}