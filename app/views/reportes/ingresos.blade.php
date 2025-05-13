@extends('layouts.reporte')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Reporte de Ingresos</h2>
    <p class="text-center">
    Del {{ $fecha_inicio->format('d/m/Y') }} 
    al {{ $fecha_fin->format('d/m/Y') }}
</p>
    
    @if($salidas->count() > 0)
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Placa</th>
                <th>Tipo de Vehículo</th>
                <th>Fecha Entrada</th>
                <th>Fecha Salida</th>
                <th>Tiempo Estacionado</th>
                <th>Total Pagado</th>
            </tr>
        </thead>
        <tbody>
        @foreach($salidas as $index => $salida)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $salida->entrada->vehiculo->placa ?? 'N/A' }}</td>
    <td>{{ $salida->entrada->vehiculo->tipo ?? 'N/A' }}</td>
    <td>
    {{ $salida->entrada->fecha_entrada_formateada }}
</td>
    <td>{{ $salida->fecha_salida_formateada ?? 'N/A' }}</td>
    <td>{{ $salida->tiempo_estacionado }}</td> {{-- Ahora usa el accesor --}}
    <td>${{ number_format($salida->total_pagado, 2) }}</td>
</tr>
@endforeach
        </tbody>
        <tfoot>
            <tr class="table-active">
                <td colspan="6" class="text-end"><strong>Total:</strong></td>
                <td><strong>${{ number_format($total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="alert alert-info text-center">
        No hay registros de ingresos para el período seleccionado
    </div>
    @endif
    
    <div class="text-end mt-4">
        <small class="text-muted">Generado el: {{ now()->format('d/m/Y H:i') }}</small>
    </div>
</div>


@endsection