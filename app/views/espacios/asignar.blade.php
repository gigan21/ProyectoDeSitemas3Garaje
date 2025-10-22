@extends('layouts.app')

@section('title', 'Asignar Espacio')
@section('page-title', 'Asignar Cliente a Espacio #'.$espacio->numero_espacio)

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('espacios.updateAsignacion', $espacio->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cliente_mensual_id">Clientes Mensuales/Ocasionales</label>
                        <select name="cliente_id" class="form-select">
    <option value="">-- Seleccione un cliente --</option>
    @foreach($clientes as $cliente)
        @php
            $tieneVehiculo = $cliente->vehiculos()->exists();
        @endphp

        <option 
            value="{{ $cliente->id }}" 
            {{ $espacio->cliente_id == $cliente->id ? 'selected' : '' }}
            style="{{ !$tieneVehiculo ? 'color: #dc3545; font-weight: bold;' : '' }}"
        >
            {{ $cliente->nombre }} ({{ $cliente->tipo_cliente }})
            @if(!$tieneVehiculo)
                ⚠️ Sin vehículo registrado
            @endif
        </option>
    @endforeach
</select>

                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Guardar Asignación</button>
                    <a href="{{ route('espacios.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection