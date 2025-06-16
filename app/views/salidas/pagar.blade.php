@extends('layouts.app')

@section('title', 'Detalle de Salida')
@section('page-title', 'Información de Salida')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Información del Vehículo</h5>
                <p><strong>Placa:</strong> {{ $salida->entrada->vehiculo->placa ?? 'N/A' }}</p>
                <p><strong>Modelo:</strong> {{ $salida->entrada->vehiculo->modelo ?? 'N/A' }}</p>
                <p><strong>Color:</strong> {{ $salida->entrada->vehiculo->color ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Información del Cliente</h5>
                <p><strong>Nombre:</strong> {{ $salida->entrada->vehiculo->cliente->nombre ?? 'N/A' }}</p>
                <p><strong>Teléfono:</strong> {{ $salida->entrada->vehiculo->cliente->telefono ?? 'N/A' }}</p>
                <p><strong>Tipo:</strong> 
                    <span class="badge {{ $salida->entrada->vehiculo->cliente->tipo_cliente === 'mensual' ? 'bg-success' : 'bg-info' }}">
                        {{ ucfirst($salida->entrada->vehiculo->cliente->tipo_cliente) }}
                    </span>
                </p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Tiempo Estacionado</h5>
                <p><strong>Entrada:</strong> {{ $salida->entrada->fecha_entrada->format('d/m/Y H:i') }}</p>
                <p><strong>Salida:</strong> {{ $salida->fecha_salida->format('d/m/Y H:i') }}</p>
                @php
                    $horas = $salida->entrada->fecha_entrada->diffInHours($salida->fecha_salida);
                    $minutos = $salida->entrada->fecha_entrada->diff($salida->fecha_salida)->format('%I');
                @endphp
                <p><strong>Duración:</strong> {{ $horas }} horas y {{ $minutos }} minutos</p>
            </div>
            <div class="col-md-6">
                <h5>Información de Pago</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Total Pagado</th>
                                <th>Fecha/Hora Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Bs {{ number_format($salida->total_pagado, 2) }}</td>
                                <td>{{ $salida->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <a href="{{ route('salidas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>
@endsection