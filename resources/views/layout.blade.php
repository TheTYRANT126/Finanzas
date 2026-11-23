<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Inicio') · Saldo</title>
    <script src="{{ asset('assets/theme.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/saldo.css') }}">
    <script src="{{ asset('assets/saldo.js') }}" defer></script>
</head>
<body>
@auth
    @php
        $navigation = [
            ['dashboard', 'dashboard', '▦', 'Inicio'],
            ['transactions.index', 'transactions.*', '⇄', 'Movimientos'],
            ['budgets.index', 'budgets.*', '▤', 'Presupuestos'],
            ['goals.index', 'goals.*', '◎', 'Metas de ahorro'],
            ['reports', 'reports', '▥', 'Reportes'],
        ];
        $initial = mb_strtoupper(mb_substr(auth()->user()->name, 0, 1));
    @endphp
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <div class="app-shell">
        <aside class="sidebar" id="navegacion">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
                <span>Saldo<small>Finanzas personales</small></span>
            </a>
            <nav aria-label="Navegación principal">
                @foreach($navigation as [$route, $pattern, $icon, $label])
                    <a href="{{ route($route) }}" @class(['nav-link', 'active' => request()->routeIs($pattern)]) @if(request()->routeIs($pattern)) aria-current="page" @endif>
                        <span class="nav-icon" aria-hidden="true">{{ $icon }}</span>{{ $label }}
                    </a>
                @endforeach
            </nav>
            <div class="sidebar-end">
                <a href="{{ route('profile.edit') }}" @class(['nav-link', 'active' => request()->routeIs('profile.*')]) @if(request()->routeIs('profile.*')) aria-current="page" @endif>
                    <span class="nav-icon" aria-hidden="true">⚙</span>Configuración
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link" type="submit"><span class="nav-icon" aria-hidden="true">⤴</span>Cerrar sesión</button>
                </form>
            </div>
        </aside>
        <div class="workspace">
            <header class="topbar">
                <button type="button" class="icon-button menu-toggle" data-menu aria-label="Mostrar navegación" aria-controls="navegacion" aria-expanded="false">☰</button>
                <span class="topbar-section">@yield('section', 'Vista general')</span>
                <div class="topbar-tools d-flex align-items-center">
                    <a href="{{ route('profile.edit') }}#preferencias" class="currency-chip" title="Cambiar moneda">{{ auth()->user()->currency }}</a>
                    <x-theme-button />
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
