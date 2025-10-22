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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="table-scroll-container">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Placa</th>
                            <th>Modelo</th>
                            <th>Color</th>
                            <th>Cliente</th>
                            <th>Tipo cliente</th>
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
                                <span class="badge {{ $vehiculo->cliente->tipo_cliente == 'mensual' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($vehiculo->cliente->tipo_cliente) }}
                                </span>
                            </td>
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
       
       </div>
</div>
@endsection
@push('styles')
<style>
    /* Imagen de fondo específica para el contenedor principal del dashboard */
    body > div > div.main-content.wrapper > main > div {
        background-image: url('{{ asset('images/itasha.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        position: relative;
        min-height: calc(100vh - 100px);
    }

    /* Overlay para mejorar la legibilidad del contenido */
    body > div > div.main-content.wrapper > main > div::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.85);
        z-index: 0;
    }

    /* Asegurar que el contenido esté por encima del fondo */
    body > div > div.main-content.wrapper > main > div > * {
        position: relative;
        z-index: 1;
    }

    /* Opcional: hacer las tarjetas un poco transparentes para que se vea el fondo */
    .dashboard-cards .card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(5px);
    }

    .chart-container,
    .recent-entries {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(5px);
    }
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
