@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <!-- Sección izquierda con imagen y mensaje de bienvenida -->
    <div class="login-left">
        <div class="welcome-content">
            <h1>Únete a nuestro equipo</h1>
            <h2>CREAR CUENTA</h2>
            <p>Crea tu cuenta de empleado para acceder al sistema de gestión de Garaje Alfaro. Únete a nuestro equipo y comienza a gestionar de manera eficiente.</p>
        </div>
        <!-- Elementos decorativos -->
        <div class="decorative-elements">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>
            <div class="line line-1"></div>
            <div class="line line-2"></div>
            <div class="line line-3"></div>
        </div>
    </div>

    <!-- Sección derecha con formulario -->
    <div class="login-right">
        <div class="login-form-container">
            <div class="login-header">
                <h2>Crear Cuenta</h2>
                <p>Completa todos los campos para crear tu cuenta de empleado y acceder al sistema.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="login-form" id="registerForm">
                @csrf
                
                <div class="form-group">
                    <input id="nombre" type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           name="nombre" 
                           placeholder="Nombre completo"
                           value="{{ old('nombre') }}"
                           required autofocus>
                    <div id="nombre-error" class="invalid-feedback" style="display: none;"></div>
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           placeholder="Correo electrónico"
                           value="{{ old('email') }}"
                           required>
                    <div id="email-error" class="invalid-feedback" style="display: none;"></div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           placeholder="Contraseña"
                           required>
                    <div id="password-error" class="invalid-feedback" style="display: none;"></div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password-confirm" type="password" 
                           class="form-control" 
                           name="password_confirmation" 
                           placeholder="Confirmar contraseña"
                           required>
                    <div id="password-confirm-error" class="invalid-feedback" style="display: none;"></div>
                </div>

                <button type="submit" class="btn-login">Registrarse</button>

                <div class="register-link">
                    <a href="{{ route('login') }}">¿Ya tienes una cuenta? Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password-confirm');

    // Función para mostrar errores
    function showError(inputId, message) {
        const input = document.getElementById(inputId);
        const errorDiv = document.getElementById(inputId + '-error');
        input.classList.add('is-invalid');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    // Función para limpiar errores
    function clearError(inputId) {
        const input = document.getElementById(inputId);
        const errorDiv = document.getElementById(inputId + '-error');
        input.classList.remove('is-invalid');
        errorDiv.style.display = 'none';
    }

    // Validación del nombre
    nombreInput.addEventListener('blur', function() {
        const nombre = this.value.trim();
        if (nombre === '') {
            showError('nombre', 'El nombre completo es obligatorio.');
        } else if (nombre.length < 3) {
            showError('nombre', 'El nombre debe tener al menos 3 caracteres.');
        } else if (nombre.length > 50) {
            showError('nombre', 'El nombre no puede exceder 50 caracteres.');
        } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre)) {
            showError('nombre', 'El nombre solo puede contener letras y espacios.');
        } else {
            clearError('nombre');
        }
    });

    // Validación del email
    emailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email === '') {
            showError('email', 'El correo electrónico no puede estar vacío.');
        } else if (!/^[^@]+@[^@]+\.[^@]+$/.test(email)) {
            showError('email', 'El formato del correo electrónico no es válido.');
        } else {
            clearError('email');
        }
    });

    // Validación de la contraseña
    passwordInput.addEventListener('blur', function() {
        const password = this.value;
        const nombre = nombreInput.value.trim();
        const email = emailInput.value.trim();
        
        if (password.length < 8) {
            showError('password', 'La contraseña debe tener al menos 8 caracteres.');
        } else if (password.startsWith(' ') || password.endsWith(' ')) {
            showError('password', 'La contraseña no puede tener espacios al inicio o al final.');
        } else if (password.toLowerCase() === nombre.toLowerCase()) {
            showError('password', 'La contraseña no puede ser igual al nombre.');
        } else if (password.toLowerCase() === email.toLowerCase()) {
            showError('password', 'La contraseña no puede ser igual al correo electrónico.');
        } else {
            clearError('password');
        }
    });

    // Validación de confirmar contraseña
    passwordConfirmInput.addEventListener('blur', function() {
        const password = passwordInput.value;
        const passwordConfirm = this.value;
        
        if (passwordConfirm !== password) {
            showError('password-confirm', 'Las contraseñas no coinciden.');
        } else {
            clearError('password-confirm');
        }
    });

    // Validación en tiempo real para confirmar contraseña
    passwordInput.addEventListener('input', function() {
        const passwordConfirm = passwordConfirmInput.value;
        if (passwordConfirm !== '' && passwordConfirm !== this.value) {
            showError('password-confirm', 'Las contraseñas no coinciden.');
        } else if (passwordConfirm === this.value) {
            clearError('password-confirm');
        }
    });

    // Validación del formulario antes de enviar
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Validar nombre
        const nombre = nombreInput.value.trim();
        if (nombre.length < 3 || nombre.length > 50 || !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre)) {
            showError('nombre', 'El nombre debe tener entre 3 y 50 caracteres y solo contener letras y espacios.');
            hasErrors = true;
        }
        
        // Validar email
        const email = emailInput.value.trim();
        if (email === '' || !/^[^@]+@[^@]+\.[^@]+$/.test(email)) {
            showError('email', 'El formato del correo electrónico no es válido.');
            hasErrors = true;
        }
        
        // Validar contraseña
        const password = passwordInput.value;
        if (password.length < 8 || password.startsWith(' ') || password.endsWith(' ')) {
            showError('password', 'La contraseña debe tener al menos 8 caracteres y no espacios al inicio o final.');
            hasErrors = true;
        } else if (password.toLowerCase() === nombre.toLowerCase() || password.toLowerCase() === email.toLowerCase()) {
            showError('password', 'La contraseña no puede ser igual al nombre o al correo electrónico.');
            hasErrors = true;
        }
        
        // Validar confirmar contraseña
        const passwordConfirm = passwordConfirmInput.value;
        if (passwordConfirm !== password) {
            showError('password-confirm', 'Las contraseñas no coinciden.');
            hasErrors = true;
        }
        
        if (hasErrors) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
