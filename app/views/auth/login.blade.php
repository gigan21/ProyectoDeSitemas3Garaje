@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <!-- Sección izquierda con imagen y mensaje de bienvenida -->
    <div class="login-left">
        <div class="welcome-content">
            <h1>QUE DIA TAN BUENO</h1>
            <h2>BIENVENIDO</h2>
            <p>Ingresa tus credenciales para acceder al sistema de gestión de Garaje Alfaro. Estamos aquí para ayudarte a gestionar tu vehículo de manera eficiente.</p>
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
                <h2>REGISTRATE</h2>
                <p>GARAJE ALFARO BUENO BONITO Y BARATO</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                
                <div class="form-group">
                    <input id="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           placeholder="Usuario o Correo Electrónico"
                           required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           placeholder="Contraseña"
                           required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Manterme Conectado</label>
                    </div>
                    <a href="#" class="forgot-password">Ya eres mienbro?</a>
                </div>

                <button type="submit" class="btn-login">Ingresar</button>

                @if (Route::has('register'))
                    <div class="register-link">
                        <a href="{{ route('register') }}">¿No tienes cuenta? Crear cuenta de empleado</a>
                    </div>
                @endif

                @if (Route::has('password.request'))
                    <div class="forgot-link">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection