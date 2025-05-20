@extends('layouts.app')

@section('title', 'Pagos Mensuales')
@section('page-title', 'Registro de Pagos')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Historial de Pagos</h4>
        <a href="{{ route('pagos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Pago
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Monto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $pago)
                <tr>
                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    <td>{{ $pago->cliente->nombre }}</td>
                    <td>Bs {{ number_format($pago->monto, 2) }}</td>
                    <td>
                        <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar pago?')">
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
@endsection