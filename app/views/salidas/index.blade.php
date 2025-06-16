@extends('layouts.app')

@section('title', 'Salidas')
@section('page-title', 'Registro de Salidas')

@section('content')
<div class="card shadow">
    <div class="card-header">
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
        
        <div class="mt-4">
            {{ $salidas->links() }}
        </div>
    </div>
</div>
@endsection