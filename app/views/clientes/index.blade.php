@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Lista de Clientes')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Registro de Clientes</h4>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>
    <div class="card-body">
        
    
    <div class="card-body">
        
        <div class="mt-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="35%">Nombre</th>
                            <th width="20%">Teléfono</th>
                            <th width="15%">Tipo</th>
                            <th width="20%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>
                                <span class="badge {{ $cliente->tipo_cliente == 'mensual' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($cliente->tipo_cliente) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-primary">
                                        Editar
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar cliente?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $clientes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos para mejorar la distribución de la tabla */
    .table th, .table td {
        vertical-align: middle;
    }
    
    /* Estilos para los botones de acción */
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    
    /* Asegurar que los botones de acción estén bien alineados */
    .d-flex.gap-2 {
        gap: 0.5rem !important;
    }
    
    /* Mejorar el aspecto de los badges */
    .badge {
        font-size: 0.875rem;
        padding: 0.35em 0.65em;
    }
    
    /* Responsive para móviles */
    @media (max-width: 767.98px) {
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.25rem !important;
        }
        
        .btn-sm {
            width: 100%;
            margin-bottom: 0.25rem;
        }
    }
    
</style>
@endpush