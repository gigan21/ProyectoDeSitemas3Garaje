@extends('layouts.app')

@section('title', 'Nuevo Vehículo')
@section('page-title', 'Registrar Vehículo')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('vehiculos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="placa">Placa</label>
                        <input type="text" name="placa" id="placa" 
                               class="form-control @error('placa') is-invalid @enderror" 
                               value="{{ old('placa') }}" required>
                        @error('placa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="modelo">Modelo</label>
                        <input type="text" name="modelo" id="modelo" 
                               class="form-control @error('modelo') is-invalid @enderror" 
                               value="{{ old('modelo') }}" required>
                        @error('modelo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" name="color" id="color" 
                               class="form-control @error('color') is-invalid @enderror" 
                               value="{{ old('color') }}" required>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <select name="cliente_id" id="cliente_id" 
                                class="form-control @error('cliente_id') is-invalid @enderror" required>
                            <option value="">-- Seleccione un cliente --</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} ({{ $cliente->telefono }})
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Registrar Vehículo</button>
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection