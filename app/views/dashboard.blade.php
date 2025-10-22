@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Panel de Control')

@section('content')

<div class="dashboard-cards">
    <!-- Tarjeta de Espacios Ocupados -->
    <div class="card card-green">
        <div class="card-icon">
            <i class="fas fa-parking"></i>
            <!-- Para usar imagen en lugar del icono, descomenta la siguiente línea -->
            <img src="{{ asset('images/transporte.png') }}" alt="Parking" class="card-image"> 
        </div>
        <div class="card-info">
            <h3>Espacios Ocupados</h3>
            <p>{{ $ocupados }} / {{ $totalEspacios }}</p>
            <div class="card-progress">
                <div class="progress-bar" style="width: {{ ($ocupados / $totalEspacios) * 100 }}%"></div>
            </div>
        </div>
    </div>
        
    <!-- Tarjeta de Clientes Mensuales -->
    <div class="card card-blue">
        <div class="card-icon">
            <i class="fas fa-users"></i>
             <img src="{{ asset('images/clientesmensuales.png') }}" alt="Clientes Mensuales" class="card-image"> 
        </div>
        <div class="card-info">
            <h3>Clientes Mensuales</h3>
            <p>{{ $clientesMensuales }}</p>
            <small class="card-subtitle">Activos este mes</small>
        </div>
    </div>
    
    <!-- Tarjeta de Clientes Ocasionales -->
    <div class="card card-yellow">
        <div class="card-icon">
            <i class="fas fa-user-clock"></i>
             <img src="{{ asset('images/ocasionales.png') }}" alt="Clientes Ocasionales" class="card-image"> 
        </div>
        <div class="card-info">
            <h3>Clientes Ocasionales</h3>
            <p>{{ $clientesOcasionales }}</p>
            <small class="card-subtitle">Registrados</small>
        </div>
    </div>
        
    <!-- Tarjeta de Ingresos Hoy -->
    <div class="card card-orange">
        <div class="card-icon">
            <i class="fas fa-money-bill-wave"></i>
             <img src="{{ asset('images/bolivar.png') }}" alt="Ingresos" class="card-image"> 
        </div>
        <div class="card-info">
            <h3>Ingresos Hoy</h3>
            <p>Bs {{ number_format($ingresosHoy, 2, '.', ',') }}</p>
            <small class="card-subtitle">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
        </div>
    </div>
    
    <!-- Tarjeta de Espacios Llenos -->
    <div class="card card-red">
        <div class="card-icon">
            <i class="fas fa-exclamation-triangle"></i>
             <img src="{{ asset('images/card-red.jpeg') }}" alt="Espacios Llenos" class="card-image"> 
        </div>
        <div class="card-info">
            <h3>Espacios Llenos</h3>
            @if($totalEspacios > 0)
                @if($espaciosLlenos >= $totalEspacios)
                    <p>¡COMPLETO!</p>
                    <small class="card-subtitle">Todos los espacios ocupados</small>
                @else
                    <p>{{ $espaciosLlenos }} / {{ $totalEspacios }}</p>
                    <small class="card-subtitle">Ocupados + Mantenimiento</small>
                @endif
            @else
                <p>No hay espacios</p>
                <small class="card-subtitle">Sin espacios configurados</small>
            @endif
        </div>
    </div>
</div>

<!-- Gráficos y Estadísticas -->
<div class="charts-section">
    <div class="chart-container">
        <h3>Ingresos de los Últimos 7 Días</h3>
        <canvas id="ingresosChart" width="400" height="200"></canvas>
    </div>
    
    <div class="chart-container">
        <h3>Ocupación por Horas del Día </h3>
        <canvas id="ocupacionChart" width="400" height="200"></canvas>
    </div>
    
  <div class="chart-container chart-center">
    <h3>Comparación Clientes</h3>
    <canvas id="clientesChart" width="500" height="200"></canvas>
</div>

<div class="chart-container">
    <h3>Ocupación por Días de la Semana</h3>
    <canvas id="ocupacionSemanalChart" width="400" height="200"></canvas>
</div>


</div>

<!-- Últimas Entradas -->
<div class="recent-entries">
    <h3>Historial de Últimas Entradas</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Hora Entrada</th>
                <th>Espacio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ultimasEntradas as $entrada)
            <tr>
                <td>{{ $entrada->vehiculo->placa }}</td>
                <td>{{ $entrada->vehiculo->modelo }}</td>
                <td>
                    @if($entrada->fecha_entrada instanceof \Carbon\Carbon)
                        {{ $entrada->fecha_entrada->format('H:i') }}
                    @else
                        {{ \Carbon\Carbon::parse($entrada->fecha_entrada)->format('H:i') }}
                    @endif
                </td>
                <td>#{{ $entrada->espacio->numero_espacio }}</td>
                <td>
                    @if($entrada->salida)
                        <span class="status-badge status-completed">Finalizado</span>
                    @else
                        <span class="status-badge status-active">Activo</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos desde el controlador
const ingresosData = @json($ingresosSemana);
const ocupacionData = @json($ocupacionHoras);
const ocupacionSemanalData = @json($ocupacionSemanal);
const clientesData = {
    mensuales: {{ $clientesMensuales }},
    ocasionales: {{ $clientesOcasionales }}
};

// Gráfico de Ingresos
const ctx1 = document.getElementById('ingresosChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ingresosData.labels,
        datasets: [{
            label: 'Ingresos (Bs)',
            data: ingresosData.data,
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Bs ' + value.toFixed(2);
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Gráfico de Ocupación por Horas
const ctx2 = document.getElementById('ocupacionChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ocupacionData.labels,
        datasets: [{
            label: 'Espacios Ocupados',
            data: ocupacionData.data,
            backgroundColor: '#007bff',
            borderColor: '#0056b3',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: {{ $totalEspacios }}
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Gráfico de Clientes (Doughnut)
const ctx3 = document.getElementById('clientesChart').getContext('2d');
new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: ['Mensuales', 'Ocasionales'],
        datasets: [{
            data: [clientesData.mensuales, clientesData.ocasionales],
            backgroundColor: ['#007bff', '#ffc107'],
            borderColor: ['#0056b3', '#e0a800'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de Ocupación por Días de la Semana
const ctx4 = document.getElementById('ocupacionSemanalChart').getContext('2d');
new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: ocupacionSemanalData.labels,
        datasets: [{
            label: 'Espacios Ocupados',
            data: ocupacionSemanalData.data,
            backgroundColor: '#ff4444',
            borderColor: '#cc0000',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: {{ $totalEspacios }}
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

<!-- Sección de Herramientas Administrativas -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools"></i> Herramientas Administrativas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('cleanup.index') }}" class="btn btn-warning btn-lg btn-block">
                            <i class="fas fa-broom"></i> Panel de Limpieza
                        </a>
                        <p class="text-muted mt-2">Gestiona la limpieza automática de clientes ocasionales y historial</p>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('reportes.index') }}" class="btn btn-info btn-lg btn-block">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                        <p class="text-muted mt-2">Genera reportes detallados del sistema</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== VARIABLES CYBERPUNK ===== */
:root {
    --cyber-blue: #00f0ff;
    --cyber-pink: #ff0088;
    --cyber-green: #00ff88;
    --cyber-yellow: #ffaa00;
    --cyber-bg: #0a0a12;
    --cyber-card: rgba(16, 20, 35, 0.95);
}

/* ===== TARJETAS CYBERPUNK MEJORADAS ===== */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.card {
    position: relative;
    background: var(--cyber-card);
    border-radius: 15px;
    padding: 25px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid transparent;
    background-clip: padding-box;
}

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 15px;
    padding: 2px;
    background: linear-gradient(135deg, var(--cyber-blue), transparent 30%, transparent 70%, var(--cyber-pink));
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    opacity: 0.7;
    transition: all 0.4s ease;
}

.card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 240, 255, 0.2);
}

.card:hover::before {
    opacity: 1;
    background: linear-gradient(135deg, var(--cyber-blue), var(--cyber-pink));
}

.card-green::before {
    background: linear-gradient(135deg, var(--cyber-green), transparent 30%, transparent 70%, var(--cyber-blue));
}

.card-blue::before {
    background: linear-gradient(135deg, var(--cyber-blue), transparent 30%, transparent 70%, var(--cyber-pink));
}

.card-yellow::before {
    background: linear-gradient(135deg, var(--cyber-yellow), transparent 30%, transparent 70%, var(--cyber-pink));
}

.card-orange::before {
    background: linear-gradient(135deg, var(--cyber-pink), transparent 30%, transparent 70%, var(--cyber-yellow));
}

.card-red::before {
    background: linear-gradient(135deg, #ff4444, transparent 30%, transparent 70%, var(--cyber-pink));
}

.card-icon {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.card-image {
    width: 50px;
    height: 50px;
    object-fit: contain;
    border-radius: 12px;
    padding: 8px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    filter: drop-shadow(0 0 10px currentColor);
}

.card:hover .card-image {
    transform: scale(1.1) rotate(5deg);
    filter: drop-shadow(0 0 15px currentColor);
}

.card-green .card-image {
    border-color: var(--cyber-green);
    box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
}

.card-blue .card-image {
    border-color: var(--cyber-blue);
    box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
}

.card-yellow .card-image {
    border-color: var(--cyber-yellow);
    box-shadow: 0 0 20px rgba(255, 170, 0, 0.3);
}

.card-orange .card-image {
    border-color: var(--cyber-pink);
    box-shadow: 0 0 20px rgba(255, 0, 136, 0.3);
}

.card-red .card-image {
    border-color: #ff4444;
    box-shadow: 0 0 20px rgba(255, 68, 68, 0.3);
}

.card-info {
    position: relative;
    z-index: 2;
}

.card-info h3 {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9em;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 10px;
}

.card-info p {
    color: white;
    font-size: 2.2em;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 0 20px currentColor;
}

.card-green .card-info p { color: var(--cyber-green); }
.card-blue .card-info p { color: var(--cyber-blue); }
.card-yellow .card-info p { color: var(--cyber-yellow); }
.card-orange .card-info p { color: var(--cyber-pink); }
.card-red .card-info p { color: #ff4444; }

.card-progress {
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    margin-top: 15px;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    height: 100%;
    border-radius: 3px;
    position: relative;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
    animation: shimmer 2s infinite;
}

.card-green .progress-bar { background: linear-gradient(90deg, var(--cyber-green), #00cc77); }
.card-blue .progress-bar { background: linear-gradient(90deg, var(--cyber-blue), #0099cc); }
.card-yellow .progress-bar { background: linear-gradient(90deg, var(--cyber-yellow), #ff8800); }
.card-orange .progress-bar { background: linear-gradient(90deg, var(--cyber-pink), #cc0066); }
.card-red .progress-bar { background: linear-gradient(90deg, #ff4444, #cc0000); }

.card-subtitle {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8em;
    margin-top: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
}

/* ===== GRÁFICOS CYBERPUNK ===== */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.chart-container {
    background: var(--cyber-card);
    padding: 25px;
    border-radius: 15px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 240, 255, 0.3);
}

.chart-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyber-blue), transparent);
    animation: scanLine 3s linear infinite;
}

.chart-container h3 {
    margin-bottom: 20px;
    color: var(--cyber-blue);
    font-size: 1.1em;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 0 0 10px var(--cyber-blue);
    font-weight: 600;
}

.chart-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.chart-center canvas {
    display: block;
    margin: 0 auto;
    filter: drop-shadow(0 0 5px var(--cyber-blue));
}

/* ===== TABLA CYBERPUNK ===== */
.recent-entries {
    background: var(--cyber-card);
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 240, 255, 0.3);
    position: relative;
}

.recent-entries::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyber-blue), transparent);
    animation: scanLine 4s linear infinite;
}

.recent-entries h3 {
    color: var(--cyber-blue);
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 0 0 10px var(--cyber-blue);
    font-weight: 600;
}

.table {
    width: 100%;
    color: rgba(255, 255, 255, 0.9);
}

.table thead th {
    background: rgba(0, 240, 255, 0.1);
    color: var(--cyber-blue);
    border-bottom: 2px solid var(--cyber-blue);
    padding: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    font-size: 0.85em;
}

.table tbody td {
    padding: 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.table tbody tr:hover td {
    background: rgba(0, 240, 255, 0.05);
    color: var(--cyber-blue);
    transform: translateX(5px);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.75em;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 1px solid;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
}

.status-completed {
    background: rgba(0, 255, 136, 0.1);
    color: var(--cyber-green);
    border-color: var(--cyber-green);
    box-shadow: 0 0 10px rgba(0, 255, 136, 0.3);
}

.status-active {
    background: rgba(255, 170, 0, 0.1);
    color: var(--cyber-yellow);
    border-color: var(--cyber-yellow);
    box-shadow: 0 0 10px rgba(255, 170, 0, 0.3);
}

/* ===== HERRAMIENTAS ADMINISTRATIVAS ===== */
.card-header {
    background: rgba(0, 240, 255, 0.1) !important;
    border-bottom: 2px solid var(--cyber-blue);
}

.card-header h5 {
    color: var(--cyber-blue);
    text-shadow: 0 0 10px var(--cyber-blue);
}

.btn {
    position: relative;
    overflow: hidden;
    border: 1px solid;
    border-radius: 10px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-warning {
    background: rgba(255, 170, 0, 0.1);
    color: var(--cyber-yellow);
    border-color: var(--cyber-yellow);
}

.btn-info {
    background: rgba(0, 240, 255, 0.1);
    color: var(--cyber-blue);
    border-color: var(--cyber-blue);
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px currentColor;
}

.text-muted {
    color: rgba(255, 255, 255, 0.6) !important;
}

/* ===== ANIMACIONES ===== */
@keyframes scanLine {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}


</style>

@endsection