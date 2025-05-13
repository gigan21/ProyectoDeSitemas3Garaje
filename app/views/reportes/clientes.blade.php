@extends('layouts.reporte')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">
        Reporte de Clientes 
        @if($tipo != 'todos')
            ({{ ucfirst($tipo) }}es)
        @endif
    </h2>
    
    @if($clientes->count() > 0)
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Tipo de Cliente</th>
                <th>Vehículos Registrados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $index => $cliente)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                <td>{{ $cliente->email ?? 'N/A' }}</td>
                <td>{{ ucfirst($cliente->tipo_cliente) }}</td>
                <td>
                    @if($cliente->vehiculos->count() > 0)
                        @foreach($cliente->vehiculos as $vehiculo)
                            {{ $vehiculo->placa }} ({{ $vehiculo->tipo }})<br>
                        @endforeach
                    @else
                        Sin vehículos
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-info text-center">
        No hay clientes registrados @if($tipo != 'todos')de este tipo @endif
    </div>
    @endif
    
    <div class="text-end mt-4">
        <small class="text-muted">Generado el: {{ now()->format('d/m/Y H:i') }}</small>
    </div>
</div>


@endsection