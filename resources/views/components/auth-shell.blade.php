@props(['title', 'description'])
<main class="auth-page">
    <section class="auth-art">
        <a href="{{ route('login') }}" class="brand">
            <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>Saldo<small>Finanzas personales</small></span>
        </a>
        <div class="auth-intro">
            <p class="auth-headline">Tus finanzas,<br>en perspectiva.</p>
            <p>Organiza tus ingresos y gastos, planifica cada mes y sigue el avance de tus metas.</p>
        </div>
        <div class="auth-graphic" aria-hidden="true">
            <div class="art-line"></div>
            <div class="art-bars"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
        </div>
    </section>
    <section class="auth-body">
        <div class="auth-tools"><x-theme-button /></div>
        <div class="auth-inner">
            <h1>{{ $title }}</h1>
            <p class="muted">{{ $description }}</p>
            <x-status />
            <x-errors />
            {{ $slot }}
        </div>
    </section>
</main>
