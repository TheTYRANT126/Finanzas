@props(['title', 'description'])
<main class="auth-page">
    <section class="auth-art">
        <a href="{{ route('login') }}" class="brand">
            <img class="brand-mark" src="{{ asset('favicon.ico') }}" width="39" height="39" alt="">
            <span>Saldo<small>Finanzas personales</small></span>
        </a>
        <div class="auth-intro">
            <p class="auth-headline">Tus finanzas,<br>en perspectiva.</p>
            <p>Organiza tus ingresos y gastos, planifica cada mes y sigue el avance de tus metas.</p>
        </div>
        <div class="auth-graphic" aria-hidden="true">
            <canvas data-auth-threads></canvas>
        </div>
    </section>
    <section class="auth-body">
        <div class="auth-inner">
            <h1>{{ $title }}</h1>
            <p class="muted">{{ $description }}</p>
            <x-status />
            <x-errors />
            {{ $slot }}
        </div>
    </section>
</main>
<script src="{{ asset('assets/auth-threads.js') }}" defer></script>
