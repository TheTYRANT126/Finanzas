<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>@yield('title', 'Inicio') · Saldo</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/saldo.css') }}">
    <script src="{{ asset('assets/saldo.js') }}" defer></script>
</head>
<body>
@auth
    @php
        $navigation = [
            ['dashboard', 'dashboard', 'home.svg', 'Inicio'],
            ['transactions.index', 'transactions.*', '⇄', 'Movimientos'],
            ['budgets.index', 'budgets.*', 'dinero.svg', 'Presupuestos'],
            ['goals.index', 'goals.*', 'meta.svg', 'Metas de ahorro'],
            ['reports', 'reports', 'reporte.svg', 'Reportes'],
        ];
        $initial = mb_strtoupper(mb_substr(auth()->user()->name, 0, 1));
    @endphp
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <div class="app-shell">
        <aside class="sidebar" id="navegacion">
            <a class="brand" href="{{ route('dashboard') }}">
                <img class="brand-mark" src="{{ asset('favicon.ico') }}" width="39" height="39" alt="">
                <span>Saldo<small>Finanzas personales</small></span>
            </a>
            <nav aria-label="Navegación principal">
                @foreach($navigation as [$route, $pattern, $icon, $label])
                    <a href="{{ route($route) }}" @class(['nav-link', 'active' => request()->routeIs($pattern)]) @if(request()->routeIs($pattern)) aria-current="page" @endif>
                        @if(str_ends_with($icon, '.svg'))
                            <img class="nav-icon nav-icon-image" src="{{ asset('assets/icons/'.$icon) }}" width="20" height="20" alt="">
                        @else
                            <span class="nav-icon" aria-hidden="true">{{ $icon }}</span>
                        @endif
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
            <div class="sidebar-end">
                <a href="{{ route('profile.edit') }}" @class(['nav-link', 'active' => request()->routeIs('profile.*')]) @if(request()->routeIs('profile.*')) aria-current="page" @endif>
                    <span class="nav-icon" aria-hidden="true">⚙</span>Configuración
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link" type="submit">
                        <img class="nav-icon nav-icon-image" src="{{ asset('assets/icons/salir.svg') }}" width="20" height="20" alt="">Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>
        <div class="workspace">
            <header class="topbar">
                <button type="button" class="icon-button menu-toggle" data-menu aria-label="Mostrar navegación" aria-controls="navegacion" aria-expanded="false">☰</button>
                <span class="topbar-section">@yield('section', 'Vista general')</span>
                <div class="topbar-tools d-flex align-items-center">
                    <x-currency-menu :currency="auth()->user()->currency" />
                    <a href="{{ route('profile.edit') }}" class="account" aria-label="Mi perfil">
                        <span class="avatar" aria-hidden="true">{{ $initial }}</span><span class="account-name">{{ auth()->user()->name }}</span>
                    </a>
                </div>
            </header>
            <main class="content" id="contenido" tabindex="-1">
                <x-status />
                @yield('content')
            </main>
        </div>
    </div>
@else
    @yield('guest')
@endauth
</body>
</html>
