<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garaje Alfaro - @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="nav-left">
                    <h1>@yield('page-title')</h1>
                </div>
                <div class="nav-right">
                    <div class="search-box">
                        <input type="text" placeholder="Buscar...">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="user-profile" style="display: flex; align-items: center; gap: 10px;">
                        <div>
                            <strong>{{ Auth::check() ? Auth::user()->nombre : 'Invitado' }}</strong><br>
                            <small>{{ Auth::check() ? ucfirst(Auth::user()->rol) : '' }}</small>

                            @auth
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; color: red; cursor: pointer; padding: 0; margin-top: 4px;">
                                        Cerrar sesión
                                    </button>
                                </form>
                            @endauth
                        </div>
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Usuario" style="width: 40px; height: 40px; border-radius: 50%;">
                    </div>
                </div>
            </nav>
            
            <!-- Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
