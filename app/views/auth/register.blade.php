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

            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf
                
                <div class="form-group">
                    <input id="nombre" type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           name="nombre" 
                           placeholder="Nombre completo"
                           value="{{ old('nombre') }}"
                           required autofocus>
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
                </div>

                <button type="submit" class="btn-login">Registrarse</button>

                <div class="register-link">
                    <a href="{{ route('login') }}">¿Ya tienes una cuenta? Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
