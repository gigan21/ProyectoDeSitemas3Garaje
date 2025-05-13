@extends('layouts.app')

@section('title', 'Nueva Entrada')
@section('page-title', 'Registrar Entrada de Vehículo')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('entradas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vehiculo_id">Vehículo</label>
                        <select name="vehiculo_id" id="vehiculo_id" class="form-control" required>
                            <option value="">Seleccione un vehículo</option>
                            @foreach($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }} - {{ $vehiculo->modelo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="espacio_id">Espacio</label>
                        <select name="espacio_id" id="espacio_id" class="form-control" required>
                            <option value="">Seleccione un espacio</option>
                            @foreach($espacios as $espacio)
                                @if($espacio->estado == 'libre')
                                    <option value="{{ $espacio->id }}">Espacio #{{ $espacio->numero_espacio }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Registrar Entrada</button>
                    <a href="{{ route('entradas.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection