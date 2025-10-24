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
                <button type="button" class="btn btn-sm btn-success" title="Aplicar descuento gratis escaneando código QR" data-espacio-id="{{ $espacio->id }}" onclick="openQRScanner({{ $espacio->id }})">
                    <i class="fas fa-badge-dollar"></i> Gratis
                </button>
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