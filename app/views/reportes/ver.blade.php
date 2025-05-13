@extends('layouts.app')

@section('title', 'Ver Reporte')
@section('page-title', 'Visualización de Reporte')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0">{{ $reporte->tipo_reporte }}</h4>
                <small>Generado el: {{ $reporte->fecha_generado->format('d/m/Y H:i') }}</small>
            </div>
            <div class="col-md-6 text-end">
                <a href="#" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </a>
                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        
        <div class="report-content">
            {!! $reporte->contenido !!}
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .top-nav, .btn {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection