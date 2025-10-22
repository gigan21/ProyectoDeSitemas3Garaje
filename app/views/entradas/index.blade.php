@extends('layouts.app')

@section('title', 'Entradas')
@section('page-title', 'Registro de Entradas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Registrar Entrada de Vehículos</h3>
                    <div class="card-tools">
                        <div>
                            <a href="{{ route('entradas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Nueva Entrada
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(request()->has('buscar') && request('buscar') != '')
                        <div class="alert alert-info mb-3">
                            Mostrando resultados para: <strong>"{{ request('buscar') }}"</strong>
                            <a href="{{ route('entradas.index') }}" class="float-right">Limpiar búsqueda</a>
                        </div>
                    @endif

                    <style>
                        .table-container {
                            max-height: 500px;
                            overflow: auto;
                            position: relative;
                        }

                        table {
                            width: max-content;
                            border-collapse: collapse;
                        }

                        th, td {
                            padding: 0.75rem;
                            min-width: 120px;
                            text-align: left;
                            vertical-align: middle;
                        }

                        /* ✅ Encabezado fijo */
                        thead th {
                            position: sticky;
                            top: 0;
                            background-color: #007bff;
                            color: white;
                            z-index: 3;
                        }

                        th.actions-column,
                        td.actions-column {
                            position: sticky;
                            right: 0;
                            background-color: white;
                            z-index: 2;
                            border-left: 1px solid #ccc;
                        }

                        /* Que el encabezado de acciones esté por encima */
                        thead th.actions-column {
                            z-index: 4;
                        }
                    </style>

                    <div class="table-container">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Vehículo</th>
                                    <th>Placa</th>
                                    <th>Espacio</th>
                                    <th>Hora Entrada</th>
                                    <th>Estado</th>
                                    <th class="actions-column">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entradas as $entrada)
                                <tr>
                                    <td>{{ $entrada->vehiculo->modelo ?? 'N/A' }}</td>
                                    <td>{{ $entrada->vehiculo->placa ?? 'N/A' }}</td>
                                    <td>#{{ $entrada->espacio->numero_espacio ?? 'N/A' }}</td>
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
                                    <td class="actions-column">
                                        <form action="{{ route('entradas.destroy', $entrada->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta entrada?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No se encontraron entradas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection