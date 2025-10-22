@extends('layouts.app')

@section('title', 'Nuevo Vehículo')
@section('page-title', 'Registrar Vehículo')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="row">
            <!-- 🧾 Columna izquierda: Formulario -->
            <div class="col-md-6">
                <form action="{{ route('vehiculos.store') }}" method="POST">
                    @csrf
                    
                    <!-- 🔥 AGREGADO: Dropdown para tipo de placa -->
                    <div class="form-group mb-3">
                        <label for="tipo_placa">Tipo de Placa</label>
                        <select name="tipo_placa" id="tipo_placa" class="form-control" required>
                            <option value="">-- Seleccione el tipo de placa --</option>
                            <option value="NUEVA">Placa Nueva (1999-2025) - Formato: 4 números + 3 letras</option>
                            <option value="ANTIGUA">Placa Antigua (antes de 1987) - Formato: 3 números + 3 letras</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="placa">Placa</label>
                        <input type="text" name="placa" id="placa" 
                               class="form-control @error('placa') is-invalid @enderror" 
                               value="{{ old('placa') }}" 
                               placeholder="Ingrese la placa según el formato seleccionado"
                               required>
                        @error('placa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
    <label for="modelo">Modelo</label>
    <select name="modelo_select" id="modelo_select" class="form-control" required>
        <option value="">-- Seleccione un modelo --</option>
        <option value="TOYOTA">TOYOTA</option>
        <option value="SUZUKI">SUZUKI</option>
        <option value="MITSUBISHI">MITSUBISHI</option>
        <option value="NISSAN">NISSAN</option>
        <option value="OTRO">OTRO</option>
    </select>

    <!-- Campo oculto que se muestra solo si selecciona OTRO -->
    <input type="text" name="modelo" id="modelo_otro"
           class="form-control mt-2 d-none"
           placeholder="Ingrese otro modelo">
</div>
<div id="otro-modelo-container" style="display:none;">
    <label for="otro_modelo">Especifique otro modelo</label>
    <input type="text" id="otro_modelo" name="otro_modelo" class="form-control">
</div>
<div class="form-group mb-3">
    <label for="color">Color</label>
    <select name="color_select" id="color_select" class="form-control" required>
        <option value="">-- Seleccione un color --</option>
        <option value="ROJO">ROJO</option>
        <option value="AZUL">AZUL</option>
        <option value="BLANCO">BLANCO</option>
        <option value="AMARILLO">AMARILLO</option>
        <option value="VERDE">VERDE</option>
        <option value="PLOMO">PLOMO</option>
        <option value="NEGRO">NEGRO</option>
        <option value="ROSADO">ROSADO</option>
        <option value="OTRO">OTRO</option>
    </select>

    <!-- Campo oculto que se muestra solo si selecciona OTRO -->
    <input type="text" name="color" id="color_otro"
           class="form-control mt-2 d-none"
           placeholder="Ingrese otro color">
</div>


                    <div class="form-group">
    <label for="cliente_id">Seleccione un Cliente</label>
    <select name="cliente_id" id="cliente_id" class="form-control" required>
        <option value="">-- Seleccione un cliente --</option>
        @foreach ($clientes as $cliente)
            <option 
                value="{{ $cliente->id }}"
                @if($ultimoCliente && $cliente->id == $ultimoCliente->id) 
                    style="background-color: #d4edda; color: #155724;" 
                @endif
            >
                {{ $cliente->nombre }} ({{ ucfirst($cliente->tipo_cliente) }})
            </option>
        @endforeach
    </select>

    @if($clientes->isEmpty())
        <small class="text-danger">⚠ No hay clientes disponibles sin vehículo registrado.</small>
    @endif
</div>


                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Registrar Vehículo</button>
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>

           <!-- 🖼️ Columna derecha: Imágenes -->
<div class="col-md-6 d-flex justify-content-center align-items-center">
    <div class="d-flex gap-4 align-items-center">
        <img src="{{ asset('images/PLACA.png') }}" 
             alt="Placa de vehículo nueva" 
             class="img-fluid rounded shadow-sm" 
             style="width: 500px; height: auto;">
        <img src="{{ asset('images/placaantigua.jpg') }}" 
             alt="Placa de vehículo antigua" 
             class="img-fluid rounded shadow-sm" 
             style="width: 500px; height: auto;">
    </div>
</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const tipoPlaca = document.getElementById('tipo_placa');
    const placa = document.getElementById('placa');
    const modeloSelect = document.getElementById('modelo_select');
    const modeloOtro = document.getElementById('modelo_otro');
    const colorSelect = document.getElementById('color_select');
    const colorOtro = document.getElementById('color_otro');

    // ✅ AGREGADO: Validación según tipo de placa seleccionada
    placa.addEventListener('input', e => {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        // Limitar longitud según tipo de placa
        if (tipoPlaca.value === 'NUEVA') {
            if (e.target.value.length > 7) e.target.value = e.target.value.slice(0, 7);
        } else if (tipoPlaca.value === 'ANTIGUA') {
            if (e.target.value.length > 6) e.target.value = e.target.value.slice(0, 6);
        }
    });

    // ✅ AGREGADO: Actualizar placeholder según tipo de placa
    tipoPlaca.addEventListener('change', e => {
        if (e.target.value === 'NUEVA') {
            placa.placeholder = 'Ej: 1234ABC (4 números + 3 letras)';
            placa.maxLength = 7;
        } else if (e.target.value === 'ANTIGUA') {
            placa.placeholder = 'Ej: 123ABC (3 números + 3 letras)';
            placa.maxLength = 6;
        } else {
            placa.placeholder = 'Ingrese la placa según el formato seleccionado';
        }
    });

    // ✅ Mostrar input si selecciona OTRO en modelo
    modeloSelect.addEventListener('change', e => {
        if (e.target.value === 'OTRO') {
            modeloOtro.classList.remove('d-none');
            modeloOtro.required = true;
        } else {
            modeloOtro.classList.add('d-none');
            modeloOtro.required = false;
            modeloOtro.value = '';
        }
    });

    // ✅ Mostrar input si selecciona OTRO en color
    colorSelect.addEventListener('change', e => {
        if (e.target.value === 'OTRO') {
            colorOtro.classList.remove('d-none');
            colorOtro.required = true;
        } else {
            colorOtro.classList.add('d-none');
            colorOtro.required = false;
            colorOtro.value = '';
        }
    });

    // ✅ Convertir "otro modelo" y "otro color" a mayúsculas
    [modeloOtro, colorOtro].forEach(input => {
        input.addEventListener('input', e => {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-ZÑÁÉÍÓÚ ]/g, '');
            if (e.target.value.length > 10) {
                e.target.value = e.target.value.slice(0, 10);
            }
        });
    });

    // 🚫 Validación extra antes de enviar el formulario
    form.addEventListener('submit', e => {
        // Validar tipo de placa seleccionado
        if (!tipoPlaca.value) {
            e.preventDefault();
            alert('Por favor seleccione el tipo de placa.');
            tipoPlaca.focus();
            return;
        }

        // Validar formato de placa según tipo seleccionado
        const placaValue = placa.value;
        let formatoValido = false;
        
        if (tipoPlaca.value === 'NUEVA') {
            formatoValido = /^[0-9]{4}[A-Z]{3}$/.test(placaValue);
            if (!formatoValido) {
                e.preventDefault();
                alert('Para placa NUEVA debe usar formato: 4 números + 3 letras\nEjemplo: 1234ABC');
                placa.focus();
                return;
            }
        } else if (tipoPlaca.value === 'ANTIGUA') {
            formatoValido = /^[0-9]{3}[A-Z]{3}$/.test(placaValue);
            if (!formatoValido) {
                e.preventDefault();
                alert('Para placa ANTIGUA debe usar formato: 3 números + 3 letras\nEjemplo: 123ABC');
                placa.focus();
                return;
            }
        }

        // Si seleccionó "OTRO" pero no llenó el campo, bloquear envío
        if (modeloSelect.value === 'OTRO' && modeloOtro.value.trim() === '') {
            e.preventDefault();
            alert('Por favor ingrese un modelo cuando seleccione "OTRO".');
            modeloOtro.focus();
            return;
        }
        if (colorSelect.value === 'OTRO' && colorOtro.value.trim() === '') {
            e.preventDefault();
            alert('Por favor ingrese un color cuando seleccione "OTRO".');
            colorOtro.focus();
            return;
        }
    });
});
</script>
@endsection