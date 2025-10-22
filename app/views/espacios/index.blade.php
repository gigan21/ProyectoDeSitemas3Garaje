@extends('layouts.app')

@section('title', 'Gestión de Espacios')
@section('page-title', 'Ocupación de Estacionamiento')

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Contenedor principal con imagen de fondo -->
<div class="espacios-background">
    <div class="card shadow espacios-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Estado Actual del Estacionamiento</h4>
            <a href="{{ route('espacios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Espacio
            </a>
        </div>
        <div class="card-body">
            <!-- Distribución del estacionamiento en cuadrado -->
            <div class="parking-layout">
                <!-- Fila superior (cajones 1-8) -->
                <div class="parking-top-row">
                    @foreach($espacios->where('numero_espacio', '<=', 8) as $espacio)
                        <div class="parking-space {{ $espacio->estado == 'ocupado' ? 'occupied' : ($espacio->estado == 'mantenimiento' ? 'maintenance' : 'available') }}">
                            <div class="space-number">{{ $espacio->numero_espacio }}</div>
                            <div class="vehicle-info">
                                {{ $espacio->estadoTexto() }}
                            </div>
                            <div class="space-actions">
                                @if($espacio->estado == 'ocupado')
                                    <form action="{{ route('espacios.liberar', $espacio->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Liberar">
                                            <i class="fas fa-door-open"></i>
                                        </button>
                                    </form>
                                    @if($espacio->cliente && $espacio->cliente->tipo_cliente === 'ocasional')
                                        <form action="{{ route('espacios.liberar-gratis', $espacio->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Aplicar descuento Gratis por ticket ≥ 100 Bs?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Gratis">
                                                <i class="fas fa-gift"></i> Gratis
                                            </button>
                                        </form>
                                    @endif
                                @elseif($espacio->estado == 'libre')
                                    <a href="{{ route('espacios.asignar', $espacio->id) }}" class="btn btn-sm btn-info" title="Asignar">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                @endif
                                <a href="{{ route('espacios.edit', $espacio->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este espacio?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Filas laterales y área central -->
                <div class="parking-middle-section">
                    <!-- Columna izquierda (cajones 9-12) -->
                    <div class="parking-left-column">
                        @foreach($espacios->whereBetween('numero_espacio', [9, 12]) as $espacio)
                            <div class="parking-space {{ $espacio->estado == 'ocupado' ? 'occupied' : ($espacio->estado == 'mantenimiento' ? 'maintenance' : 'available') }}">
                                <div class="space-number">{{ $espacio->numero_espacio }}</div>
                                @if($espacio->estado == 'ocupado' && $espacio->cliente)
                                    <div class="vehicle-info">
                                        {{ $espacio->estadoTexto() }}
                                    </div>
                                @endif
                                <div class="space-actions">
                                    @if($espacio->estado == 'ocupado')
                                        <form action="{{ route('espacios.liberar', $espacio->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Liberar">
                                                <i class="fas fa-door-open"></i>
                                            </button>
                                        </form>
                                        @if($espacio->cliente && $espacio->cliente->tipo_cliente === 'ocasional')
                                            <form action="{{ route('espacios.liberar-gratis', $espacio->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Aplicar descuento Gratis por ticket ≥ 100 Bs?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Gratis">
                                                    <i class="fas fa-gift"></i> Gratis
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($espacio->estado == 'libre')
                                        <a href="{{ route('espacios.asignar', $espacio->id) }}" class="btn btn-sm btn-info" title="Asignar">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('espacios.edit', $espacio->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este espacio?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Área central (entrada/salida) -->
                    <div class="parking-center">
                        <div class="parking-entrance">
                            
                            <i class="fas fa-car fa-3x text-secondary"></i>
                            <div class="entrance-text">ÁREA DE<br>CIRCULACIÓN</div>
                            <div class="arrows">
                                <i class="fas fa-arrow-up text-success"></i>
                                <i class="fas fa-arrow-down text-danger"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha (cajones 13-16) -->
                    <div class="parking-right-column">
                        @foreach($espacios->whereBetween('numero_espacio', [13, 16]) as $espacio)
                            <div class="parking-space {{ $espacio->estado == 'ocupado' ? 'occupied' : ($espacio->estado == 'mantenimiento' ? 'maintenance' : 'available') }}">
                                <div class="space-number">{{ $espacio->numero_espacio }}</div>
                                <div class="vehicle-info">
                                    {{ $espacio->estadoTexto() }}
                                </div>
                                <div class="space-actions">
                                    @if($espacio->estado == 'ocupado')
                                        <form action="{{ route('espacios.liberar', $espacio->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Liberar">
                                                <i class="fas fa-door-open"></i>
                                            </button>
                                        </form>
                                        @if($espacio->cliente && $espacio->cliente->tipo_cliente === 'ocasional')
                                            <form action="{{ route('espacios.liberar-gratis', $espacio->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Aplicar descuento Gratis por ticket ≥ 100 Bs?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Gratis">
                                                    <i class="fas fa-gift"></i> Gratis
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($espacio->estado == 'libre')
                                        <a href="{{ route('espacios.asignar', $espacio->id) }}" class="btn btn-sm btn-info" title="Asignar">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('espacios.edit', $espacio->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este espacio?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Fila inferior (cajones 17 en adelante) -->
                <div class="parking-bottom-row">
                    @foreach($espacios->where('numero_espacio', '>=', 17) as $espacio)
                        <div class="parking-space {{ $espacio->estado == 'ocupado' ? 'occupied' : ($espacio->estado == 'mantenimiento' ? 'maintenance' : 'available') }}">
                             <!-- Imagen del auto según el estado -->
            <div class="car-image">
                <img src="{{ $espacio->estado == 'ocupado' ? asset('images/card-red.jpeg') : 
                            ($espacio->estado == 'mantenimiento' ? asset('images/coche.png') : 
                            asset('images/coche2.png')) }}" 
                     alt="Espacio {{ $espacio->numero_espacio }}"
                     class="car-img">
                <div class="space-number">{{ $espacio->numero_espacio }}</div>
            </div>
                        <!-- Información del vehículo si está ocupado -->
                        @if($espacio->estado == 'ocupado' && $espacio->cliente)
                            <div class="vehicle-info">
                                {{ $espacio->estadoTexto() }}
                            </div>
                        @endif
                        <div class="space-actions">
                            @if($espacio->estado == 'ocupado')
                                <form action="{{ route('espacios.liberar', $espacio->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Liberar">
                                        <i class="fas fa-door-open"></i>
                                    </button>
                                </form>
                                @if($espacio->cliente && $espacio->cliente->tipo_cliente === 'ocasional')
                                    <form action="{{ route('espacios.liberar-gratis', $espacio->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Aplicar descuento Gratis por ticket ≥ 100 Bs?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Gratis">
                                            <i class="fas fa-gift"></i> Gratis
                                        </button>
                                    </form>
                                @endif
                            @elseif($espacio->estado == 'libre')
                                <a href="{{ route('espacios.asignar', $espacio->id) }}" class="btn btn-sm btn-info" title="Asignar">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                            @endif
                            <a href="{{ route('espacios.edit', $espacio->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este espacio?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

       <div class="d-flex justify-content-center mt-4">
    @if ($espacios->currentPage() > 1)
        <a href="{{ $espacios->previousPageUrl() }}" class="btn btn-secondary me-2">Página Anterior</a>
    @endif
    @for ($i = 1; $i <= $espacios->lastPage(); $i++)
        <a href="{{ $espacios->url($i) }}" class="btn {{ $i == $espacios->currentPage() ? 'btn-primary' : 'btn-light' }}">
            {{ $i }}
        </a>
    @endfor
    @if ($espacios->hasMorePages())
        <a href="{{ $espacios->nextPageUrl() }}" class="btn btn-primary ms-2">Siguiente Página</a>
    @endif
</div>
        </div>
    </div>
</div>



<style>
    /* Estilos para la imagen de fondo */
    .espacios-background {
        padding: 20px 0;
    }
    
    .espacios-card {
        position: relative;
        background-image: url('{{ asset('images/ESPACIOSA1.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }
    
    .espacios-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.75);
        z-index: 1;
    }
    
    .card-header {
        position: relative;
        z-index: 2;
        background: rgba(248, 249, 250, 0.95);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .card-body {
        position: relative;
        z-index: 2;
    }
    
    .parking-layout {
        background: rgba(245, 247, 250, 0.85);
        padding: 30px;
        border-radius: 15px;
        border: 3px solid rgba(224, 224, 224, 0.8);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        max-width: 900px;
        margin: 0 auto;
    }
    
    .parking-top-row, .parking-bottom-row {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 12px;
        margin-bottom: 15px;
    }
    
    .parking-bottom-row {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 8px;              /* reduce la separación */
    margin-top: 10px;
}
    .parking-middle-section {
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        gap: 15px;
        min-height: 320px;
    }
    
    .parking-left-column, .parking-right-column {
        display: grid;
        grid-template-rows: repeat(4, 1fr);
        gap: 12px;
    }
    
    .parking-center {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    border: 3px dashed #fff;
    position: relative;
    overflow: hidden;
    background-image: url('{{ asset('images/mapeo.jpeg') }}');
    background-size: 120% 120%; /* 🔥 Solo agranda la imagen */
    background-position: center;
    background-repeat: no-repeat;
    min-height: 300px; /* 🔥 Altura fija independiente */
    width: 500px; /* 🔥 Ancho fijo */
}
    
    .parking-center::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255,255,255,0.1) 10px,
            rgba(255,255,255,0.1) 20px
        );
    }
    
    .parking-entrance {
        text-align: center;
        color: white;
        z-index: 1;
        position: relative;
    }
    
    .entrance-text {
        font-weight: bold;
        font-size: 1.1rem;
        margin: 10px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .arrows {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 10px;
    }
    
    .arrows i {
        font-size: 1.5rem;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }
    
    .parking-space {
        border: 3px solid #dee2e6;
        border-radius: 12px;
        padding: 10px;
        text-align: center;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        background: rgba(255, 255, 255, 0.92);
    }
    
    .parking-space:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        background: rgba(255, 255, 255, 0.96);
    }
    
    .parking-space.available {
        background: linear-gradient(135deg, rgba(232, 245, 233, 0.9) 0%, rgba(200, 230, 201, 0.9) 100%);
        border-color: #4caf50;
    }
    
    .parking-space.occupied {
        background: linear-gradient(135deg, rgba(255, 235, 238, 0.9) 0%, rgba(255, 205, 210, 0.9) 100%);
        border-color: #f44336;
    }
    
    .parking-space.maintenance {
        background: linear-gradient(135deg, rgba(255, 248, 225, 0.9) 0%, rgba(255, 224, 130, 0.9) 100%);
        border-color: #ff9800;
    }
    
    .space-number {
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 8px;
        background: rgba(0,0,0,0.1);
        border-radius: 20px;
        padding: 8px 12px;
        color: #333;
    }
    
    .vehicle-info {
        font-size: 0.9rem;
        margin: 10px 0;
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        color: #555;
        min-height: 30px;
    }
    
    /* BOTONES MEJORADOS */
    .space-actions {
        margin-top: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }
    
    .space-actions .btn {
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 8px 16px;
        min-width: 90px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        border: 2px solid transparent;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .space-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    /* Botón Liberar */
    .space-actions .btn-danger {
        background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
        color: white;
        border-color: #ff4757;
    }
    
    .space-actions .btn-danger:hover {
        background: linear-gradient(135deg, #ff3838 0%, #ff2f2f 100%);
        box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4);
    }
    
    /* Botón Asignar */
    .space-actions .btn-info {
        background: linear-gradient(135deg, #3742fa 0%, #2f3542 100%);
        color: white;
        border-color: #3742fa;
    }
    
    .space-actions .btn-info:hover {
        background: linear-gradient(135deg, #2f3542 0%, #1e2124 100%);
        box-shadow: 0 4px 15px rgba(55, 66, 250, 0.4);
    }
    
    /* Botón Editar */
    .space-actions .btn-warning {
        background: linear-gradient(135deg,rgb(40, 121, 56) 0%,rgb(30, 222, 66) 100%);
        color: white;
        border-color:rgb(12, 11, 8);
    }
    
    .space-actions .btn-warning:hover {
        background: linear-gradient(135deg, #ff7675 0%, #fd79a8 100%);
        box-shadow: 0 4px 15px rgba(255, 165, 2, 0.4);
    }
    
    /* Botón Eliminar */
    .space-actions .btn-outline-danger {
        background: transparent;
        color: #dc3545;
        border-color: #dc3545;
        border-width: 2px;
    }
    
    .space-actions .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }
    
    /* Agregar texto a los botones */
    .btn[title="Liberar"]::after {
        content: " Liberar";
        margin-left: 4px;
    }
    
    .btn[title="Asignar"]::after {
        content: " Asignar";
        margin-left: 4px;
    }
    
    .btn[title="Editar"]::after {
        content: " Editar";
        margin-left: 4px;
    }
    
    .btn[title="Eliminar"]::after {
        content: " Eliminar";
        margin-left: 4px;
    }
    
    /* Responsive Design Mejorado */
    @media (max-width: 1200px) {
        .parking-layout {
            padding: 20px;
        }
        
        .parking-top-row, .parking-bottom-row {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .parking-middle-section {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .parking-left-column, .parking-right-column {
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 1fr;
        }
        
        .parking-center {
            min-height: 150px;
        }
        
        .parking-space {
            min-height: 140px;
        }
        
        
    }
    
    @media (max-width: 768px) {
        .parking-top-row, .parking-bottom-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .parking-left-column, .parking-right-column {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .parking-space {
            min-height: 130px;
            padding: 12px;
        }
        
        .space-actions .btn {
            font-size: 0.8rem;
            padding: 6px 12px;
            min-width: 80px;
        }
        
        .entrance-text {
            font-size: 0.9rem;
        }
        
        
    }
    
    @media (max-width: 576px) {
        .parking-layout {
            padding: 15px;
        }
        
        .parking-top-row, .parking-bottom-row,
        .parking-left-column, .parking-right-column {
            grid-template-columns: 1fr;
        }
        
        .parking-space {
            min-height: 120px;
            padding: 10px;
        }
        
        .space-actions {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .space-actions .btn {
            font-size: 0.75rem;
            padding: 5px 10px;
            min-width: 70px;
        }
        
       
    }
    
    /* Efectos adicionales para mejorar la experiencia visual */
    .parking-space {
        position: relative;
        overflow: hidden;
    }
    
    .parking-space::before {
        content: '';
        position: absolute;
        top: -2px;1
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: inherit;
        border-radius: inherit;
        z-index: -1;
        filter: blur(8px);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .parking-space:hover::before {
        opacity: 0.7;
    }
    
    /* Estado de carga para botones */
    .space-actions .btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    /* Estilos para la imagen del auto */
.car-image {
    position: relative;
    width: 100%;
    height: 100px;
    margin-bottom: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.car-img {
    width: 100%;
    max-width: 120px;
    height: auto;
    transition: all 0.3s ease;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
}

.parking-space:hover .car-img {
    transform: scale(1.05) translateY(-5px);
    filter: drop-shadow(0 6px 12px rgba(0,0,0,0.3));
}

.space-number {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(17, 6, 216, 0.7);
    color: white;
    border-radius: 50%;
    width: 15px;
    height: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
    z-index: 2;
}

/* Ajustar el contenedor del espacio */
.parking-space {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    min-height: 200px; /* Aumentar altura para acomodar la imagen */
}

/* Efectos según estado */
.parking-space.available .car-img {
    animation: pulse-green 2s infinite;
}

.parking-space.occupied .car-img {
    animation: shake 0.5s ease-in-out infinite alternate;
}

.parking-space.maintenance .car-img {
    animation: pulse-yellow 2s infinite;
}

@keyframes pulse-green {
    0% { filter: drop-shadow(0 0 5px rgba(76, 175, 80, 0)); }
    50% { filter: drop-shadow(0 0 15px rgba(76, 175, 80, 0.7)); }
    100% { filter: drop-shadow(0 0 5px rgba(76, 175, 80, 0)); }
}

@keyframes pulse-yellow {
    0% { filter: drop-shadow(0 0 5px rgba(255, 193, 7, 0)); }
    50% { filter: drop-shadow(0 0 15px rgba(255, 193, 7, 0.7)); }
    100% { filter: drop-shadow(0 0 5px rgba(255, 193, 7, 0)); }
}

@keyframes shake {
    0% { transform: translateX(-3px); }
    100% { transform: translateX(3px); }
}


</style>
@endsection