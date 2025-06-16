<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/fondo.jpeg') }}" alt="Garaje Alfaro">
        <h2>Garaje Alfaro</h2>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('clientes*') ? 'active' : '' }}">
                <a href="{{ route('clientes.index') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                    </span>
                    <span>Clientes</span>
                </a>
            </li>
            <li class="{{ request()->is('vehiculos*') ? 'active' : '' }}">
                <a href="{{ route('vehiculos.index') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM19 17H5v-5h14v5z"/>
                            <circle cx="7.5" cy="14.5" r="1.5"/>
                            <circle cx="16.5" cy="14.5" r="1.5"/>
                        </svg>
                    </span>
                    <span>Vehículos</span>
                </a>
            </li>
            <li class="{{ request()->is('espacios*') ? 'active' : '' }}">
                <a href="{{ route('espacios.index') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M18 4v16H6V4h12m0-2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                            <path d="M11 7h2v2h-2zm0 4h2v2h-2zm0 4h2v2h-2z"/>
                        </svg>
                    </span>
                    <span>Espacios</span>
                </a>
            </li>
            <li class="{{ request()->is('entradas*') ? 'active' : '' }}">
                <a href="{{ route('entradas.index') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11 16h2v-4h4v-2h-4V6h-2v4H7v2h4v4zm1 6C6.48 22 2 17.52 2 12S6.48 2 12 2s10 4.48 10 10-4.48 10-10 10zm0-18c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8z"/>
                        </svg>
                    </span>
                    <span>Entradas</span>
                </a>
            </li>
            <li class="{{ request()->is('salidas*') ? 'active' : '' }}">
                <a href="{{ route('salidas.index') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                        </svg>
                    </span>
                    <span>Salidas</span>
                </a>
            </li>
            <li class="{{ request()->is('pagos*') ? 'active' : '' }}">
                <a href="{{ route('pagos.index') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                        </svg>
                    </span>
                    <span>Pagos Mensuales</span>
                </a>
            </li>
            <li class="{{ request()->is('reportes*') ? 'active' : '' }}">
                <a href="{{ route('reportes.index') }}">
                    <span class="svg-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                        </svg>
                    </span>
                    <span>Reportes</span>
                </a>
            </li>
        </ul>
    </nav>
    
   
</div>
 
<STYLE>
/* Estilos para los iconos SVG */
    .svg-icon {
        width: 24px;
        height: 24px;
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .svg-icon svg {
        width: 100%;
        height: 100%;
        fill: currentColor;
        opacity: 0.8;
        transition: all 0.3s;
    }

    .sidebar-nav li.active .svg-icon svg,
    .sidebar-nav li a:hover .svg-icon svg {
        opacity: 1;
        transform: scale(1.1);
    }

    .sidebar-nav li span {
        font-size: 0.95rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 70px;
        }
        
        .sidebar-header h2,
        .sidebar-nav li span {
            display: none;
        }
        
        .logo-icon svg {
            width: 30px;
            height: 30px;
        }
        
        .sidebar-nav li a {
            justify-content: center;
            padding: 15px 0;
        }
        
        .svg-icon {
            margin-right: 0;
            width: 22px;
            height: 22px;
        }
    }

</STYLE>