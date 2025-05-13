<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Espacio;
use App\Models\Cliente;
use App\Models\Entrada;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEspacios = Espacio::count();
        $ocupados = Espacio::where('estado', 'ocupado')->count();
        $clientesMensuales = Cliente::where('tipo_cliente', 'mensual')->count();
        $clientesOcasionales = Cliente::where('tipo_cliente', 'ocasional')->count();

        $ingresosHoy = Entrada::whereDate('fecha_entrada', Carbon::today())
            ->with('salida')
            ->get()
            ->sum(function($entrada) {
                return $entrada->salida ? $entrada->salida->total_pagado : 0;
            });
            
            $ultimasEntradas = Entrada::with(['vehiculo', 'espacio'])
            ->orderBy('fecha_entrada', 'desc')
            ->take(5)
            ->get();
        
            
        return view('dashboard', compact(
            'totalEspacios',
            'ocupados',
            'clientesMensuales',
            'clientesOcasionales',
            'ingresosHoy',
            'ultimasEntradas'
        ));
    }
}