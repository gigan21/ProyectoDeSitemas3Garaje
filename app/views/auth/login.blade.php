@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-box">
        <div class="login-header">
            <img src="{{ asset('images/logo-garaje-alfaro.png') }}" alt="Garaje Alfaro">
            <h2>Iniciar Sesión</h2>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group remember">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Recordarme</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Ingresar</button>
            @if (Route::has('password.change'))
    <a class="btn btn-link" href="{{ route('password.change') }}">¿Deseas cambiar tu contraseña?</a>
@endif

            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
        </form>
    </div>
</div>
@endsection