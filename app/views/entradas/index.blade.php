@extends('layouts.app')

@section('title', 'Entradas')
@section('page-title', 'Registro de Entradas')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Historial de Entradas</h4>
        <a href="{{ route('entradas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Entrada
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Vehículo</th>
                        <th>Placa</th>
                        <th>Espacio</th>
                        <th>Hora Entrada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entradas as $entrada)
                    <tr>
                        <td>{{ $entrada->vehiculo->modelo }}</td>
                        <td>{{ $entrada->vehiculo->placa }}</td>
                        <td>#{{ $entrada->espacio->numero_espacio }}</td>
                        <td>
                        @isset($entrada->fecha_entrada)
    {{ \Carbon\Carbon::parse($entrada->fecha_entrada)->format('d/m/Y H:i') }}
@else
    Fecha no disponible
@endisset

</td>
                        <td>
                            @if($entrada->salida)
                                <span class="badge bg-secondary">Salida registrada</span>
                            @else
                                <span class="badge bg-success">En parqueo</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $entradas->links() }}
        </div>
    </div>
</div>
@endsection