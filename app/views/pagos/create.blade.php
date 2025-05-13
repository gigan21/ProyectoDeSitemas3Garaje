@extends('layouts.app')

@section('title', 'Registrar Pago')
@section('page-title', 'Nuevo Pago ')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('pagos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="form-control" required>
                            <option value="">-- Seleccione un cliente --</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nombre }} ({{ $cliente->telefono }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="monto">Monto ($)</label>
                        <input type="number" step="0.01" name="monto" id="monto" 
                               class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_pago">Fecha de Pago</label>
                        <input type="date" name="fecha_pago" id="fecha_pago" 
                               class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Registrar Pago</button>
                    <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection