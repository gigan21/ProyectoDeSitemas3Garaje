@extends('layouts.reporte')

@section('report-content')
<div class="company-header">
    <img src="{{ storage_path('app/public/logo.png') }}" style="height: 60px;">
    <div class="company-info">
        <h2 style="margin: 0; color: #2c3e50;">Reporte de Clientes</h2>
        <small style="color: #7f8c8d;">{{ now()->format('d/m/Y H:i') }}</small>
    </div>
</div>

<div style="margin: 30px 0; text-align: center;">
    <div style="display: inline-block; background: #f1f8fe; padding: 10px 30px; border-radius: 30px;">
        <strong>Filtro aplicado:</strong> 
        <span class="badge 
            @if($tipo == 'todos') badge-primary
            @elseif($tipo == 'mensual') badge-success
            @else badge-warning @endif">
            {{ $tipo == 'todos' ? 'Todos los clientes' : ($tipo == 'mensual' ? 'Clientes Mensuales' : 'Clientes Ocasionales') }}
        </span>
    </div>
</div>

@if($clientes->count() > 0)
<!-- Estadísticas rápidas -->
<div style="display: flex; justify-content: space-around; margin-bottom: 30px;">
    <div style="text-align: center;">
        <div style="font-size: 36px; font-weight: bold; color: #3498db;">{{ $clientes->count() }}</div>
        <div style="color: #7f8c8d;">Clientes registrados</div>
    </div>
    <div style="text-align: center;">
        <div style="font-size: 36px; font-weight: bold; color: #2ecc71;">{{ $clientes->where('tipo_cliente', 'mensual')->count() }}</div>
        <div style="color: #7f8c8d;">Clientes mensuales</div>
    </div>
    <div style="text-align: center;">
        <div style="font-size: 36px; font-weight: bold; color: #e74c3c;">{{ $clientes->where('tipo_cliente', 'ocasional')->count() }}</div>
        <div style="color: #7f8c8d;">Clientes ocasionales</div>
    </div>
</div>

<table class="report-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Contacto</th>
            <th>Tipo</th>
            <th>Vehículos</th>
            <th>Actividad</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $index => $cliente)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <strong>{{ $cliente->nombre }}</strong><br>
                <small>CI: {{ $cliente->ci ?? 'N/D' }}</small>
            </td>
            <td>
                {{ $cliente->telefono ?? 'N/D' }}<br>
                <small>{{ $cliente->email ?? 'Sin email' }}</small>
            </td>
            <td>
                <span class="badge 
                    @if($cliente->tipo_cliente == 'mensual') badge-success
                    @else badge-warning @endif">
                    {{ ucfirst($cliente->tipo_cliente) }}
                </span>
            </td>
            <td>
                @if($cliente->vehiculos->count() > 0)
                    @foreach($cliente->vehiculos as $vehiculo)
                        <span class="badge badge-primary" style="display: block; margin-bottom: 3px;">
                            {{ $vehiculo->placa }} ({{ $vehiculo->tipo }})
                        </span>
                    @endforeach
                @else
                    <span class="badge" style="background: #95a5a6;">Sin vehículos</span>
                @endif
            </td>
            <td>
   @php
    $ultimaVisita = $cliente->entradas ? $cliente->entradas->sortByDesc('created_at')->first() : null;
@endphp
    @if($ultimaVisita)
        <small>Últ. visita:</small><br>
        {{ $ultimaVisita->fecha_entrada->format('d/m/Y') }}
    @else
        <span class="badge" style="background: #e74c3c;">Nunca ha visitado</span>
    @endif
</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="report-footer">
    <div style="border-top: 1px solid #eee; padding-top: 10px; margin-top: 20px;">
        <div style="display: flex; justify-content: space-between;">
            <div>Generado por: {{ Auth::user()->name }}</div>
            <div>Página 1 de 1</div>
        </div>
    </div>
</div>
@else
<div style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 8px; text-align: center;">
    No se encontraron clientes con los filtros aplicados
</div>
@endif
@endsection