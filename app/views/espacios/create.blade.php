@extends('layouts.app')

@section('title', 'Crear Espacio')
@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5>Nuevo Espacio</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('espacios.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="numero_espacio" class="form-label">Número de Espacio</label>
                    <input type="text" 
                           class="form-control @error('numero_espacio') is-invalid @enderror" 
                           id="numero_espacio" 
                           name="numero_espacio" 
                           value="{{ old('numero_espacio') }}" 
                           required>
                    
                    {{-- Mostrar error si el número de espacio está duplicado o tiene otro problema --}}
                    @error('numero_espacio')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="libre">Libre</option>
                        <option value="ocupado">Ocupado</option>
                        <option value="mantenimiento">Mantenimiento</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('espacios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection