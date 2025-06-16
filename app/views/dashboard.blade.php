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
</div>

<!-- Gráficos y Estadísticas -->
<div class="charts-section">
    <div class="chart-container">
        <h3>Ingresos de los Últimos 7 Días</h3>
        <canvas id="ingresosChart" width="400" height="200"></canvas>
    </div>
    
    <div class="chart-container">
        <h3>Ocupación por Horas del Día Regresion Lineal</h3>
        <canvas id="ocupacionChart" width="400" height="200"></canvas>
    </div>
    
  <div class="chart-container chart-center">
    <h3>Comparación Clientes</h3>
    <canvas id="clientesChart" width="500" height="200"></canvas>
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
</script>

<style>
/* Estilos adicionales para las mejoras */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    position: relative;
    overflow: hidden;
}

.card-image {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 8px;
    border: 2px solid;
}

.card-green .card-image {
    border-color: #28a745;
}

.card-blue .card-image {
    border-color: #007bff;
}

.card-yellow .card-image {
    border-color: #ffc107;
}

.card-orange .card-image {
    border-color: #fd7e14;
}

.card-progress {
    width: 100%;
    height: 4px;
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
    margin-top: 10px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.card-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.85em;
    margin-top: 5px;
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-container h3 {
    margin-bottom: 15px;
    color: #333;
    font-size: 1.1em;
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
}
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8em;
    font-weight: 500;
}

.status-completed {
    background-color: #d4edda;
    color: #155724;
}

.status-active {
    background-color: #fff3cd;
    color: #856404;
}

.recent-entries {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .chart-container canvas {
        max-height: 300px;
    }
}


</style>
@endsection