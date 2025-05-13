@extends('layouts.app')

@section('title', 'Salidas')
@section('page-title', 'Registro de Salidas')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Historial de Salidas</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Vehículo</th>
                        <th>Placa</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Total Pagado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salidas as $salida)
                    <tr>
                        <td>{{ $salida->entrada->vehiculo->modelo }}</td>
                        <td>{{ $salida->entrada->vehiculo->placa }}</td>
                        <td>{{ Carbon\Carbon::parse($salida->entrada->fecha_entrada)->format('d/m/Y H:i') }}</td>
                        <td>{{ Carbon\Carbon::parse($salida->fecha_salida)->format('d/m/Y H:i') }}</td>
                        <td>${{ number_format($salida->total_pagado, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $salidas->links() }}
        </div>
    </div>
</div>
@endsection