@extends('layouts.app')

@section('title', 'Registrar Salida')
@section('page-title', 'Salida de Vehículos Mensuales')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('salidas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="entrada_id">VehículoO Mensual</label>
                        <select name="entrada_id" id="entrada_id" class="form-control" required>
                            <option value="">Seleccione un vehículo mensual</option>
                            @foreach($entradas as $entrada)
                                @if(!$entrada->salida)
                                    <option value="{{ $entrada->id }}">
                                        {{ $entrada->vehiculo->placa }} - Entrada: {{ $entrada->fecha_entrada->format('H:i') }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="total_pagado" value="0">
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Registrar Salida</button>
                    <a href="{{ route('salidas.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection