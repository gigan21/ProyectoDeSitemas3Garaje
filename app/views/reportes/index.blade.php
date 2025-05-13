@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Generar Reportes')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reporte de Ingresos</h5>
                        <form action="{{ route('reportes.generar') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="tipo_reporte" value="ingresos">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Generar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reporte de Clientes</h5>
                        <form action="{{ route('reportes.generar') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="tipo_reporte" value="clientes">
                            <div class="form-group">
                                <label for="tipo_cliente">Tipo de Cliente</label>
                                <select name="tipo_cliente" class="form-control">
                                    <option value="todos">Todos</option>
                                    <option value="mensual">Mensuales</option>
                                    <option value="ocasional">Ocasionales</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Generar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection