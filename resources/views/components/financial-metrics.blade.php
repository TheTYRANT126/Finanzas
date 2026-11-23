@props(['totals', 'month'])
@php($currency = auth()->user()->currency)
<div class="metric-grid">
    <section class="metric metric-hero">
        <div class="metric-top"><h2>Ahorro neto</h2><span class="metric-icon" aria-hidden="true">≈</span></div>
        <strong>{{ \App\Services\Money::format($totals['net']) }}</strong>
        <span>Ingresos menos gastos · {{ $currency }}</span>
    </section>
    <section class="metric">
        <div class="metric-top"><h2>Ingresos del mes</h2><span class="metric-icon mint" aria-hidden="true">↙</span></div>
        <strong>{{ \App\Services\Money::format($totals['income']) }}</strong>
        <span>{{ ucfirst($month->translatedFormat('F Y')) }}</span>
    </section>
    <section class="metric">
        <div class="metric-top"><h2>Gastos del mes</h2><span class="metric-icon peach" aria-hidden="true">↗</span></div>
        <strong>{{ \App\Services\Money::format($totals['expense']) }}</strong>
        <span>{{ ucfirst($month->translatedFormat('F Y')) }}</span>
    </section>
</div>
