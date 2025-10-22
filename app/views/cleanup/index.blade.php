@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-broom"></i> Panel de Limpieza del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Limpieza de Clientes Ocasionales -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-users"></i> Clientes Ocasionales
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Elimina los registros de clientes ocasionales que ya han salido del parqueo (mantiene salidas).
                                    </p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Clientes eliminados</li>
                                        <li><i class="fas fa-check text-success"></i> Vehículos eliminados</li>
                                        <li><i class="fas fa-check text-success"></i> Salidas preservadas</li>
                                    </ul>
                                    <form action="{{ route('cleanup.ocasional-clients') }}" method="POST" onsubmit="return confirm('¿Estás seguro de ejecutar la limpieza de clientes ocasionales?')">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-block">
                                            <i class="fas fa-broom"></i> Limpiar Clientes Ocasionales
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Limpieza de Salidas Ocasionales -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-sign-out-alt"></i> Salidas Ocasionales
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Elimina los registros de salidas de clientes ocasionales del historial.
                                    </p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Salidas eliminadas</li>
                                        <li><i class="fas fa-check text-success"></i> Entradas eliminadas</li>
                                        <li><i class="fas fa-check text-success"></i> Solo clientes ocasionales</li>
                                    </ul>
                                    <form action="{{ route('cleanup.ocasional-salidas') }}" method="POST" onsubmit="return confirm('¿Estás seguro de ejecutar la limpieza de salidas de clientes ocasionales?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-broom"></i> Limpiar Salidas Ocasionales
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Limpieza del Historial -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history"></i> Historial Antiguo
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Elimina registros de entradas y salidas de clientes ocasionales más antiguos que 1 día.
                                    </p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Entradas eliminadas</li>
                                        <li><i class="fas fa-check text-success"></i> Salidas eliminadas</li>
                                        <li><i class="fas fa-check text-success"></i> Solo registros antiguos</li>
                                    </ul>
                                    <form action="{{ route('cleanup.history') }}" method="POST" onsubmit="return confirm('¿Estás seguro de ejecutar la limpieza del historial?')">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-block">
                                            <i class="fas fa-broom"></i> Limpiar Historial
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Limpieza Completa -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-trash-alt"></i> Limpieza Completa
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Ejecuta ambas limpiezas: clientes ocasionales y historial antiguo.
                                    </p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Clientes ocasionales</li>
                                        <li><i class="fas fa-check text-success"></i> Historial antiguo</li>
                                        <li><i class="fas fa-check text-success"></i> Limpieza completa</li>
                                    </ul>
                                    <form action="{{ route('cleanup.system') }}" method="POST" onsubmit="return confirm('¿Estás seguro de ejecutar la limpieza completa del sistema? Esta acción no se puede deshacer.')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-broom"></i> Limpieza Completa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Sistema -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle"></i> Información del Sistema
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6><i class="fas fa-clock"></i> Programación Automática</h6>
                                            <ul class="list-unstyled">
                                                <li><strong>Limpieza diaria:</strong> 2:00 AM</li>
                                                <li><strong>Limpieza de clientes:</strong> 2:30 AM</li>
                                                <li><strong>Historial:</strong> Registros > 1 día</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6><i class="fas fa-shield-alt"></i> Seguridad</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check text-success"></i> Solo clientes ocasionales</li>
                                                <li><i class="fas fa-check text-success"></i> Preserva clientes mensuales</li>
                                                <li><i class="fas fa-check text-success"></i> Transacciones seguras</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
