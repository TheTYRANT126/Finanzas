@props(['month', 'route'])
<form method="get" action="{{ route($route) }}" class="month-form" data-period>
    <label class="visually-hidden" for="period-month">Periodo mensual</label>
    <input id="period-month" type="month" name="month" value="{{ $month->format('Y-m') }}" min="1900-01" max="2099-12">
    <button type="submit" class="button secondary compact period-submit">Ver mes</button>
</form>
