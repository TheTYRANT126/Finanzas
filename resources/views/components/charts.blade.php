@props(['totals', 'flow'])
<div class="chart-grid">
    <section class="panel chart-panel">
        <div class="panel-heading">
            <div><h2>Flujo de efectivo</h2><p>Ingresos y gastos acumulados por día</p></div>
        </div>
        @if($totals['income'] || $totals['expense'])
            <div class="chart-key"><span><i class="line-income"></i> Ingresos</span><span><i class="line-expense"></i> Gastos</span></div>
            <div class="line-chart">
                <canvas role="img" tabindex="0" aria-label="Líneas de ingresos y gastos acumulados. Usa las flechas del teclado para consultar cada día." data-currency="{{ auth()->user()->currency }}" data-flow="{{ json_encode($flow, JSON_THROW_ON_ERROR) }}">Las cifras del mes aparecen en el resumen.</canvas>
                <output class="chart-tooltip" hidden></output>
            </div>
        @else
            <x-empty title="Sin movimientos en este periodo" description="Registra un ingreso o un gasto para ver el flujo del mes." icon="▥" class="chart-empty" />
        @endif
    </section>
    <section class="panel chart-panel">
        <div class="panel-heading">
            <div><h2>Gastos por categoría</h2><p>Distribución del gasto del mes</p></div>
        </div>
        @if($totals['expense'])
            @php
                $angle = 0;
                $gradient = $totals['categories']->map(function ($item) use (&$angle, $totals) {
                    $start = $angle;
                    $angle += $item['total'] / $totals['expense'] * 360;
                    return $item['color'].' '.$start.'deg '.$angle.'deg';
                })->implode(', ');
            @endphp
            <div class="donut" style="--segments: {{ $gradient }}" role="img" aria-label="Distribución del gasto por categoría. El detalle sigue debajo.">
                <div class="donut-center"><span>Total</span><strong>{{ \App\Services\Money::format($totals['expense']) }}</strong></div>
            </div>
            <ul class="legend">
                @foreach($totals['categories'] as $item)
                    <li><i style="--dot: {{ $item['color'] }}"></i><span>{{ $item['name'] }}</span><strong>{{ \App\Services\Money::format($item['total']) }}</strong><small>{{ $item['percent'] }}%</small></li>
                @endforeach
            </ul>
        @else
            <x-empty title="Todavía no hay gastos" description="Aquí aparecerá su distribución por categoría." icon="◔" class="chart-empty" />
        @endif
    </section>
</div>
