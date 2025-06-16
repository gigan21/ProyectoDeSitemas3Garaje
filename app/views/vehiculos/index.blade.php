@extends('layouts.app')

@section('title', 'Vehículos')
@section('page-title', 'Registro de Vehículos')

@section('content')
<div class="card shadow card-table">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-car"></i> Vehículos Registrados
        </h4>
        <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Vehículo
        </a>
    </div>
  <div class="card-body">
    <div class="table-scroll-container">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th>Cliente</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehiculos as $vehiculo)
                    <tr>
                        <td class="font-weight-bold">{{ $vehiculo->placa }}</td>
                        <td>{{ $vehiculo->modelo }}</td>
                        <td>
                           <span class="badge" style="color: {{ $vehiculo->color }};">
    {{ ucfirst($vehiculo->color) }}
</span>

                        </td>
                        <td>{{ $vehiculo->cliente->nombre }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" 
                                   class="btn btn-sm btn-warning d-flex align-items-center"
                                   title="Editar">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                                <form action="{{ route('vehiculos.destroy', $vehiculo->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger d-flex align-items-center"
                                            onclick="return confirm('¿Estás seguro de eliminar este vehículo?')"
                                            title="Eliminar">
                                        <i class="fas fa-trash mr-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> {{-- END table-scroll-container --}}
    @if($vehiculos->hasPages())
    <div class="card-footer">
        {{ $vehiculos->links() }}
    </div>
    @endif
</div>
@endsection
@push('styles')
<style>
    /* Altura máxima + scroll */
    .table-scroll-container {
        max-height: 400px;
        overflow-y: auto;
        overflow-x: auto;
        margin-bottom: 1rem;
    }

    /* Encabezado fijo al hacer scroll */
    .table thead th {
        position: sticky;
        top: 0;
        background-color: var(--bs-primary, #0d6efd); /* Asegura color de fondo */
        color: white;
        z-index: 2; /* Encima del tbody */
    }

    /* Alineación general */
    .table th, .table td {
        vertical-align: middle;
    }

    /* Ajustes visuales */
    .badge {
        font-size: 0.875rem;
        padding: 0.35em 0.65em;
    }

    /* Botones alineados */
    .d-flex.gap-2 {
        gap: 0.5rem !important;
    }

    .btn-sm {
        min-width: 90px;
        text-align: center;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        height: 38px;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }

    @media (max-width: 767.98px) {
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.25rem !important;
        }

        .btn-sm {
            width: 100%;
            min-width: unset;
        }

        .table-scroll-container {
            max-height: 300px;
        }
    }
</style>
@endpush
