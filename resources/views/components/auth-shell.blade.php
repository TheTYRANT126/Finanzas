@props(['title', 'description'])
<main class="auth-page">
    <section class="auth-art">
        <div class="auth-identity">
            <a href="{{ route('login') }}" class="brand">
                <img class="brand-mark" src="{{ asset('favicon.ico') }}" width="128" height="128" alt="">
                <span>Saldo<small>Finanzas personales</small></span>
            </a>
            <p class="auth-headline">Organiza tus finanzas</p>
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
