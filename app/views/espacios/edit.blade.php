@extends('layouts.app')

@section('title', 'Editar Espacio')
@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5>Editar Espacio #{{ $espacio->numero_espacio }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('espacios.update', $espacio->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="numero_espacio" class="form-label">Número de Espacio</label>
                    <input type="text" class="form-control" id="numero_espacio" name="numero_espacio" value="{{ $espacio->numero_espacio }}" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="libre" {{ $espacio->estado == 'libre' ? 'selected' : '' }}>Libre</option>
                        <option value="ocupado" {{ $espacio->estado == 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                        <option value="mantenimiento" {{ $espacio->estado == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning">Actualizar</button>
                <a href="{{ route('espacios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection