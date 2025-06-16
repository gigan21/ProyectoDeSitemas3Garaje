<?php

namespace App\Http\Controllers;

use App\Models\PagoMensual;
use Illuminate\Http\Request;
use App\Models\Espacio;
use App\Models\Cliente;
use App\Models\Entrada;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalEspacios = Espacio::count();
        $ocupados = Espacio::where('estado', 'ocupado')->count();
        $clientesMensuales = Cliente::where('tipo_cliente', 'mensual')->count();
        $clientesOcasionales = Cliente::where('tipo_cliente', 'ocasional')->count();

        // Ingresos de hoy (manteniendo tu lógica original)
        $ingresosHoySalidas = Entrada::whereDate('fecha_entrada', Carbon::today())
            ->with('salida')
            ->get()
            ->sum(function($entrada) {
                return $entrada->salida ? $entrada->salida->total_pagado : 0;
            });

        $ingresosHoyPagos = PagoMensual::whereDate('fecha_pago', Carbon::today())->sum('monto');
        $ingresosHoy = $ingresosHoySalidas + $ingresosHoyPagos;

        // Buscar en las entradas
        $busqueda = $request->input('buscar');

        $ultimasEntradas = Entrada::with(['vehiculo', 'espacio', 'salida'])
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->whereHas('vehiculo', function ($q) use ($busqueda) {
                    $q->where('placa', 'like', "%$busqueda%")
                      ->orWhere('modelo', 'like', "%$busqueda%");
                });
            })
            ->orderBy('fecha_entrada', 'desc')
            ->take(5)
            ->get();

        // Datos para gráfico de ingresos de los últimos 7 días
        $ingresosSemana = $this->getIngresosUltimos7Dias();
        
        // Datos para gráfico de ocupación por horas
        $ocupacionHoras = $this->getOcupacionPorHoras();

        return view('dashboard', compact(
            'totalEspacios',
            'ocupados',
            'clientesMensuales',
            'clientesOcasionales',
            'ingresosHoy',
            'ultimasEntradas',
            'busqueda',
            'ingresosSemana',
            'ocupacionHoras'
        ));
    }

    private function getIngresosUltimos7Dias()
    {
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $labels[] = $fecha->format('d/m');
            
            // Ingresos por salidas
            $ingresosSalidas = Entrada::whereDate('fecha_entrada', $fecha)
                ->with('salida')
                ->get()
                ->sum(function($entrada) {
                    return $entrada->salida ? $entrada->salida->total_pagado : 0;
                });
            
            // Ingresos por pagos mensuales
            $ingresosPagos = PagoMensual::whereDate('fecha_pago', $fecha)->sum('monto');
            
            $data[] = $ingresosSalidas + $ingresosPagos;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getOcupacionPorHoras()
    {
        $labels = [];
        $data = [];
        
        // Generar datos para las últimas 24 horas por intervalos
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);
            
            // Contar entradas activas en esa hora del día actual
            $ocupacion = Entrada::whereTime('fecha_entrada', '>=', sprintf('%02d:00:00', $hour))
                ->whereTime('fecha_entrada', '<', sprintf('%02d:00:00', ($hour + 1) % 24))
                ->whereDate('fecha_entrada', Carbon::today())
                ->whereDoesntHave('salida') // Solo los que no han salido
                ->count();
            
            $data[] = $ocupacion;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}