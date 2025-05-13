@extends('layouts.app')

@section('title', 'Gestión de Espacios')
@section('page-title', 'Listado de Espacios')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Espacios de Estacionamiento</h4>
        <a href="{{ route('espacios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Espacio
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($espacios as $espacio)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-{{ $espacio->estado == 'ocupado' ? 'danger' : ($espacio->estado == 'mantenimiento' ? 'warning' : 'success') }}">
                    <div class="card-header bg-{{ $espacio->estado == 'ocupado' ? 'danger' : ($espacio->estado == 'mantenimiento' ? 'warning' : 'success') }} text-white">
                        <h5 class="card-title mb-0">Espacio {{ $espacio->numero_espacio }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Estado:</span>
                            <span class="badge bg-{{ $espacio->estado == 'ocupado' ? 'danger' : ($espacio->estado == 'mantenimiento' ? 'warning' : 'success') }}">
                                {{ ucfirst($espacio->estado) }}
                            </span>
                        </div>
                        
                        @if($espacio->cliente)
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Cliente:</span>
                            <span>{{ $espacio->cliente->nombre }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between">
                        <a href="{{ route('espacios.edit', $espacio->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        @if($espacio->estado == 'ocupado')
    <form action="{{ route('espacios.liberar', $espacio->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-door-open"></i> Liberar
        </button>
    </form>
                        @else
                            <a href="{{ route('espacios.asignar', $espacio->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-user-plus"></i> Asignar
                            </a>
                        @endif
                        
                        <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este espacio?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection