@extends('layouts.reporte')

@section('report-content')
<div class="company-header">
    <div>
        <h1 style="color: #2c3e50; margin: 0;">Garaje Alfaro</h1>
        <p style="color: #7f8c8d; margin: 5px 0;">Sistema de Gestión de Estacionamiento</p>
    </div>
    <div class="company-info">
        <small>REF:78993992 </small><br>
        <small>Av. Principal, Calle Junin.Quillacollo</small><br>
        <small>Teléfono: 62533078</small>
    </div>
</div>

<div class="report-header">
    <h1 class="report-title">Reporte de Ingresos</h1>
    <p class="report-subtitle">Resumen financiero de operaciones</p>
    <div class="report-period">
        Del {{ $fecha_inicio->format('d/m/Y') }} al {{ $fecha_fin->format('d/m/Y') }}
    </div>
</div>

@if($salidas->count() > 0)
<!-- Resumen estadístico -->
<div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
    <div style="background: #3498db; color: white; padding: 15px; border-radius: 8px; width: 30%; text-align: center;">
        <h3 style="margin: 0 0 5px 0; font-size: 16px;">Total Ingresos</h3>
        <p style="margin: 0; font-size: 24px; font-weight: bold;">Bs {{ number_format($total, 2) }}</p>
    </div>
    <div style="background: #2ecc71; color: white; padding: 15px; border-radius: 8px; width: 30%; text-align: center;">
        <h3 style="margin: 0 0 5px 0; font-size: 16px;">Vehículos Atendidos</h3>
        <p style="margin: 0; font-size: 24px; font-weight: bold;">{{ $salidas->count() }}</p>
    </div>
    <div style="background: #9b59b6; color: white; padding: 15px; border-radius: 8px; width: 30%; text-align: center;">
        <h3 style="margin: 0 0 5px 0; font-size: 16px;">Promedio por Vehículo</h3>
        <p style="margin: 0; font-size: 24px; font-weight: bold;">Bs {{ number_format($total/$salidas->count(), 2) }}</p>
    </div>
</div>

<!-- Gráfico simple CSS -->
<div class="chart-container">
    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px;">Distribución por Tipo de Vehículo</h3>
    @php
        $tipos = $salidas->groupBy(function($item) {
            return $item->entrada->vehiculo->tipo ?? 'Otros';
        });
    @endphp
    
    @foreach($tipos as $tipo => $items)
    <div style="margin-bottom: 10px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <span>{{ $tipo }}</span>
            <span>{{ $items->count() }} ({{ number_format($items->count()/$salidas->count()*100, 1) }}%)</span>
        </div>
        <div class="chart-bar">
            <div class="chart-fill" style="width: {{ $items->count()/$salidas->count()*100 }}%"></div>
        </div>
    </div>
    @endforeach
</div>

<!-- Tabla de datos -->
<table class="report-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Placa</th>
            <th>Tipo</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Tiempo</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($salidas as $index => $salida)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $salida->entrada->vehiculo->placa ?? 'N/A' }}</td>
            <td>
                <span class="badge 
                    @if(($salida->entrada->vehiculo->tipo ?? '') == 'Automóvil') badge-primary
                    @elseif(($salida->entrada->vehiculo->tipo ?? '') == 'Motocicleta') badge-success
                    @else badge-warning @endif">
                    {{ $salida->entrada->vehiculo->tipo ?? 'N/A' }}
                </span>
            </td>
            <td>{{ $salida->entrada->fecha_entrada_formateada }}</td>
            <td>{{ $salida->fecha_salida_formateada ?? 'N/A' }}</td>
            <td>{{ $salida->tiempo_estacionado }}</td>
            <td style="font-weight: bold;">Bs {{ number_format($salida->total_pagado, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="report-total">
            <td colspan="5"></td>
            <td style="text-align: right;">Total:</td>
            <td>Bs {{ number_format($total, 2) }}</td>
        </tr>
    </tfoot>
</table>
@else
<div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 8px; text-align: center;">
    No hay registros de ingresos para el período seleccionado
</div>
@endif

<div class="report-footer">
    Generado el {{ now()->format('d/m/Y H:i') }} | Sistema Garaje Alfaro v1.5
</div>
@endsection