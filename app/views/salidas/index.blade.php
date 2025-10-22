@extends('layouts.app')

@section('title', 'Salidas')
@section('page-title', 'Registro de Salidas')

@section('content')
<div class="card salidas-card shadow">
    <div class="salidas-gif-background"></div>
    <div class="card-header sticky-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Listado de Salidas</h3>
            <div>
                <a href="{{ route('salidas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Registrar Salida Vehiculos Ocasionales
                </a>
                <a href="{{ route('salidas.create-mensual') }}" class="btn btn-success">
    Registrar Salida Vehciulos Mensuales
</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehículo placa</th>
                        <th>Cliente</th>
                        <th>Tipo Cliente</th>
                        <th>Fecha Salida</th>
                        <th>Total Pagado (Bs)</th>
                        <th>Gratis</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salidas as $salida)
                    <tr>
                        <td>{{ $salida->id }}</td>
                        <td>{{ $salida->entrada->vehiculo->placa ?? 'N/A' }}</td>
                        <td>{{ $salida->entrada->vehiculo->cliente->nombre ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $salida->entrada->vehiculo->cliente->tipo_cliente === 'mensual' ? 'bg-success' : 'bg-info' }}">
                                {{ ucfirst($salida->entrada->vehiculo->cliente->tipo_cliente) ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $salida->fecha_salida->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($salida->total_pagado, 2) }}</td>
                        <td>
                            @if(isset($salida->es_gratis) && $salida->es_gratis)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('salidas.pagar', $salida->id) }}" class="btn btn-info btn-sm" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('salidas.destroy', $salida->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta salida?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        
    </div>
</div>

<style>
    .card.salidas-card {
    background: rgb(247, 250, 250);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    border: none;
    position: relative;
    overflow: hidden;
}

.salidas-gif-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('{{ asset('images/SALIDA.gif') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 1;
    z-index: 0;
    pointer-events: none;
}

.card-header,
.card-body {
    position: relative;
    z-index: 1;
}
/* Estilos para el header sticky */
.sticky-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: linear-gradient(135deg, rgba(0, 212, 255, 0.1) 0%, rgba(255, 0, 110, 0.05) 100%);
    backdrop-filter: blur(15px);
    border-bottom: 2px solid rgba(0, 212, 255, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.sticky-header:hover {
    background: linear-gradient(135deg, rgba(0, 212, 255, 0.15) 0%, rgba(255, 0, 110, 0.08) 100%);
    box-shadow: 0 6px 25px rgba(0, 212, 255, 0.2);
}

.sticky-header .card-title {
    color: var(--primary);
    text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    font-weight: 600;
}

.sticky-header .btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.sticky-header .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.sticky-header .btn-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border: 1px solid var(--primary);
    color: white;
}

.sticky-header .btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    box-shadow: 0 4px 20px rgba(0, 212, 255, 0.4);
}

.sticky-header .btn-success {
    background: linear-gradient(135deg, var(--success) 0%, #00cc6a 100%);
    border: 1px solid var(--success);
    color: white;
}

.sticky-header .btn-success:hover {
    background: linear-gradient(135deg, #00cc6a 0%, var(--success) 100%);
    box-shadow: 0 4px 20px rgba(0, 255, 136, 0.4);
}

/* Asegurar que la tabla tenga scroll independiente */
.table-responsive {
    max-height: 70vh;
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Estilos para la tabla con tema cyberpunk */
.table {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 8px;
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    font-weight: 600;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
    border: none;
    padding: 15px 12px;
}

.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0, 212, 255, 0.1);
}

.table tbody tr:hover {
    background: linear-gradient(135deg, rgba(0, 212, 255, 0.05) 0%, rgba(255, 0, 110, 0.02) 100%);
    transform: translateX(5px);
    box-shadow: 0 2px 10px rgba(0, 212, 255, 0.1);
}

.table tbody td {
    padding: 12px;
    vertical-align: middle;
    border: none;
}

/* Estilos para badges */
.badge {
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    text-shadow: 0 0 5px currentColor;
}

.badge.bg-success {
    background: linear-gradient(135deg, var(--success) 0%, #00cc6a 100%) !important;
    box-shadow: 0 2px 10px rgba(0, 255, 136, 0.3);
}

.badge.bg-info {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
    box-shadow: 0 2px 10px rgba(0, 212, 255, 0.3);
}

/* Estilos para botones de acción */
.btn-sm {
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin: 0 2px;
}

.btn-info {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border: 1px solid var(--primary);
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    box-shadow: 0 4px 15px rgba(0, 212, 255, 0.4);
    transform: translateY(-2px);
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger) 0%, #cc0630 100%);
    border: 1px solid var(--danger);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #cc0630 0%, var(--danger) 100%);
    box-shadow: 0 4px 15px rgba(255, 7, 58, 0.4);
    transform: translateY(-2px);
}

/* Responsive design */
@media (max-width: 768px) {
    .sticky-header .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .sticky-header .btn {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .table-responsive {
        max-height: 60vh;
    }
}
</style>
@endsection