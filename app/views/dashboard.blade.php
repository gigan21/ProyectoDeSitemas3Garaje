@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-cards">
    <!-- Tarjeta de Espacios Ocupados -->
    <div class="card card-green">
        <div class="card-icon">
            <i class="fas fa-parking"></i>
        </div>
        <div class="card-info">
            <h3>Espacios Ocupados</h3>
            <p>{{ $ocupados }} / {{ $totalEspacios }}</p>
        </div>
    </div>
    
    <!-- Tarjeta de Clientes Mensuales -->
    <div class="card card-blue">
        <div class="card-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="card-info">
            <h3>Clientes Mensuales</h3>
            <p>{{ $clientesMensuales }}</p>
        </div>
    </div>
    <!-- Tarjeta de Clientes Ocasionales -->
<div class="card card-yellow">
    <div class="card-icon">
        <i class="fas fa-user-clock"></i>
    </div>
    <div class="card-info">
        <h3>Clientes Ocasionales</h3>
        <p>{{ $clientesOcasionales }}</p>
    </div>
</div>

    <!-- Tarjeta de Ingresos Hoy -->
    <div class="card card-orange">
        <div class="card-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="card-info">
            <h3>Ingresos Hoy</h3>
            <p>Bs {{ number_format($ingresosHoy, 2, '.', ',') }}</p>
        </div>
    </div>
</div>

<!-- Últimas Entradas -->
<div class="recent-entries">
    <h3>Historial de Ultimas Entradas</h3>
        

    <table class="table">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Hora Entrada</th>
                <th>Espacio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ultimasEntradas as $entrada)
            <tr>
                <td>{{ $entrada->vehiculo->placa }}</td>
                <td>{{ $entrada->vehiculo->modelo }}</td>
                <td>
                    @if($entrada->fecha_entrada instanceof \Carbon\Carbon)
                        {{ $entrada->fecha_entrada->format('H:i') }}
                    @else
                        {{ \Carbon\Carbon::parse($entrada->fecha_entrada)->format('H:i') }}
                    @endif
                </td>
                <td>#{{ $entrada->espacio->numero_espacio }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection