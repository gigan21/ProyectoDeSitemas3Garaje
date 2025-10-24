<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Garaje Alfaro - @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
    @auth
        <!-- Estilos específicos del modo oscuro -->
        <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
    @endauth
</head>
<body class="@auth @if(Cookie::get('darkMode') === 'enabled') dark-mode @endif @endauth">
    
    @auth
        <!-- LAYOUT PARA USUARIOS AUTENTICADOS -->
        <div class="dashboard-container">
            <!-- Sidebar -->
            @include('layouts.sidebar')
            
            <!-- Main Content -->
            <div class="main-content wrapper">
                <!-- Top Navigation -->
                <nav class="top-nav">
    <div class="nav-left">
        <h1>@yield('page-title')</h1>
    </div>
    
    <!-- Barra de búsqueda centrada -->
    <div class="nav-center">
        @php
            $currentSegment = Request::segment(1);
            $searchAction = url('/'); 
            $resourceRoutes = [
                'clientes' => 'clientes.index',
                'vehiculos' => 'vehiculos.index', 
                'entradas' => 'entradas.index',
                'salidas' => 'salidas.index',
                'pagos' => 'pagos.index',
                'espacios' => 'espacios.index',
            ];
            if (array_key_exists($currentSegment, $resourceRoutes)) {
                $searchAction = route($resourceRoutes[$currentSegment]);
            } else {
                $searchAction = url()->current();
            }
        @endphp
        
        <form action="{{ $searchAction }}" method="GET" class="search-box">
            <input type="text" name="buscar" placeholder="Buscar..." value="{{ request('buscar') }}">
            <button type="submit">
                <img src="{{ asset('images/LUPA.png') }}" alt="Buscar" style="width: 30px; height: 30px;">
            </button>
            @if(request()->has('buscar') && request('buscar') != '')
                <a href="{{ $searchAction }}" class="clear-button">Limpiar</a>
            @endif
        </form>
    </div>
    
    <!-- Perfil de usuario a la derecha -->
    <div class="nav-right">
        <div class="user-profile">
            <div>
                <strong>{{ Auth::check() ? ucfirst(Auth::user()->rol) : '' }}</strong>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: red; cursor: pointer; padding: 0; margin-top: 4px;">
                            Cerrar sesión
                        </button>
                    </form>
                @endauth
            </div>
            <img src="{{ asset('images/gara.jpeg') }}" alt="Usuario" style="width: 40px; height: 40px; border-radius: 50%;">
        </div>
    </div>
</nav>
                    
                
                <!-- Botón de modo oscuro (posición fija) -->
                <div class="dark-mode-toggle">
                    <button id="darkModeToggle" class="toggle-btn">
                        <i class="fas fa-moon"></i>
                        <i class="fas fa-sun"></i>
                    </button>
                </div>
                
                <!-- Contenido principal -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
        
        <!-- Script para el modo oscuro -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const darkModeToggle = document.getElementById('darkModeToggle');
                const body = document.body;
                
                // Verificar preferencias al cargar
                function checkDarkModePreference() {
                    const savedPreference = localStorage.getItem('darkMode');
                    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    
                    if (savedPreference === 'enabled' || (!savedPreference && systemPrefersDark)) {
                        body.classList.add('dark-mode');
                    }
                }
                
                // Inicializar verificando preferencias
                checkDarkModePreference();
                
                // Manejar clic en el botón de toggle
                darkModeToggle.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');
                    const isDarkMode = body.classList.contains('dark-mode');
                    
                    // Guardar preferencia
                    localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
                    document.cookie = `darkMode=${isDarkMode ? 'enabled' : 'disabled'}; path=/; max-age=${60*60*24*365}`;
                });
                
                // Opcional: Escuchar cambios en las preferencias del sistema
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                    if (!localStorage.getItem('darkMode')) {
                        if (e.matches) {
                            body.classList.add('dark-mode');
                        } else {
                            body.classList.remove('dark-mode');
                        }
                    }
                });
            });
        </script>
        
        @stack('scripts')
    @else
        <!-- LAYOUT PARA USUARIOS NO AUTENTICADOS (LOGIN/REGISTER) -->
        @yield('content')
        @stack('scripts')
    @endauth
</body>
</html>