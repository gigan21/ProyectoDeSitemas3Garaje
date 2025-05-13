<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo-garaje-alfaro.png') }}" alt="Garaje Alfaro">
        <h2>Garaje Alfaro</h2>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('clientes*') ? 'active' : '' }}">
                <a href="{{ route('clientes.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                </a>
            </li>
            <li class="{{ request()->is('vehiculos*') ? 'active' : '' }}">
                <a href="{{ route('vehiculos.index') }}">
                    <i class="fas fa-car"></i>
                    <span>Vehículos</span>
                </a>
            </li>
            <li class="{{ request()->is('espacios*') ? 'active' : '' }}">
                <a href="{{ route('espacios.index') }}">
                    <i class="fas fa-parking"></i>
                    <span>Espacios</span>
                </a>
            </li>
            <li class="{{ request()->is('entradas*') ? 'active' : '' }}">
                <a href="{{ route('entradas.index') }}">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Entradas</span>
                </a>
            </li>
            <li class="{{ request()->is('salidas*') ? 'active' : '' }}">
                <a href="{{ route('salidas.index') }}">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Salidas</span>
                </a>
            </li>
            <li class="{{ request()->is('pagos*') ? 'active' : '' }}">
                <a href="{{ route('pagos.index') }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Pagos</span>
                </a>
            </li>
            <li class="{{ request()->is('reportes*') ? 'active' : '' }}">
                <a href="{{ route('reportes.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            
        </ul>
    </nav>
    
   
</div>