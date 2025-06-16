@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Lista de Clientes')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Registrar Clientes</h4>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>
    <div class="card-body">
        <div class="mt-4">
            {{-- NEW DIV FOR SCROLLING --}}
            <div class="table-scroll-container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="10%">ID</th>
                                <th width="35%">Nombre</th>
                                <th width="20%">Teléfono</th>
                                <th width="12%">Tipo</th>
                                <th width="35%" class="text-center">Acciones</th>
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
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-primary px-3">
                                            Editar
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger px-3" onclick="return confirm('¿Eliminar cliente?')">
                                                Eliminar
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
            {{ $clientes->links() }}
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
        min-width: 90px; /* Ajusta este valor si 'Eliminar' o 'Editar' no caben bien */
        text-align: center; /* Centra el texto horizontalmente */
        display: inline-flex; /* Usa flexbox para fácil centrado vertical y horizontal */
        justify-content: center; /* Centra el contenido horizontalmente para flexbox */
        align-items: center; /* Centra el contenido verticalmente para flexbox */
        height: 38px; /* Establece una altura fija para que ambos botones tengan la misma altura */
        padding: 0.25rem 0.75rem; /* Mantén el padding original o ajústalo según necesites */
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

    /* Estilos para la tabla */
    .table {
        /* Remove table-layout: fixed; and width: 100%; from here if you want horizontal scrolling */
        /* table-layout: fixed; */
        /* width: 100%; */
    }

    .table td {
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table td:last-child {
        white-space: nowrap;
    }

    /* Estilos para el contenedor de la tabla con scroll */
    .table-scroll-container {
        max-height: 400px; /* Define una altura máxima para la tabla */
        overflow-y: auto; /* Habilita el scroll vertical cuando el contenido excede la altura máxima */
        overflow-x: auto; /* Habilita el scroll horizontal si la tabla es más ancha que el contenedor */
        margin-bottom: 1rem; /* Espacio debajo del contenedor de la tabla */
    }

    /* Asegura que el thead se mantenga fijo al hacer scroll vertical */
    .table thead th {
        position: sticky;
        top: 0;
        background-color: var(--bs-primary, #0d6efd); /* Usa el color de fondo de tu thead, o #0d6efd si es Bootstrap 5 primary */
        z-index: 1; /* Asegura que el thead esté por encima del tbody al hacer scroll */
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
            min-width: unset;
        }
        /* Ajusta la altura máxima para móviles si es necesario */
        .table-scroll-container {
            max-height: 300px; /* Una altura menor para pantallas pequeñas */
        }
    }
</style>
@endpush