@extends('layouts.reporte')

@section('report-content')
    <div class="report-header">
        <h1 class="report-title">Reporte de Espacios</h1>
        <h2 class="report-subtitle">Resumen de utilización de espacios</h2>
        <div class="report-period">
            {{ $fecha_inicio }} - {{ $fecha_fin }}
        </div>
    </div>

    <div>
        <h5>Total de Espacios: {{ $totalEspacios }}</h5>
        <h5>Espacios Ocupados: {{ $espaciosOcupados }}</h5>
        <h5>Espacios Libres: {{ $espaciosLibres }}</h5>
        <h5>Porcentaje de Uso: {{ number_format($porcentajeUso, 2) }}%</h5>
        <h5>Espacio Más Utilizado: 
            @if($espacioMasUtilizado)
                #{{ $espacioMasUtilizado->numero_espacio }} ({{ $espacioMasUtilizado->entradas_count }} entradas)
            @else
                N/A
            @endif
        </h5>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Número de Espacio</th>
                <th>Estado</th>
                <th>Cliente Asignado</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($espacios as $espacio)
                <tr>
                    <td>#{{ $espacio->numero_espacio }}</td>
                    <td>
                        <span class="badge {{ $espacio->estado == 'ocupado' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($espacio->estado) }}
                        </span>
                    </td>
                    <td>{{ $espacio->cliente ? $espacio->cliente->nombre : 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($espacio->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="report-footer">
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
@endsection