@props(['budget', 'full' => false])
@php
    $label = $budget->category_detail ?? $budget->category->name;
    $spent = $budget->spent_cents;
    $percentage = $budget->consumption($spent);
    $remaining = $budget->limit_cents - $spent;
    $tone = $remaining <= 0 ? 'danger' : ($spent / $budget->limit_cents >= .8 ? 'warning' : 'good');
@endphp
<article class="progress-item">
    <div class="progress-head">
        <h3>{{ $label }} @if($budget->category_detail !== null)<small class="muted">· {{ $budget->category->name }}</small>@endif</h3>
        <span @class(['badge', $tone])>{{ $percentage }}%</span>
    </div>
    <progress class="progress {{ $tone }}" value="{{ min($spent, $budget->limit_cents) }}" max="{{ $budget->limit_cents }}" aria-label="Presupuesto utilizado en {{ $label }}"></progress>
    <div class="progress-caption">
        <span><strong>{{ \App\Services\Money::format($spent) }}</strong> de {{ \App\Services\Money::format($budget->limit_cents) }}</span>
        <span>{{ $remaining < 0 ? 'Exceso de ' : 'Disponible ' }}{{ \App\Services\Money::format(abs($remaining)) }}</span>
    </div>
    @if($full)
        <div class="card-actions">
            <a class="text-link" href="{{ route('budgets.edit', $budget) }}">Editar</a>
            <form method="post" action="{{ route('budgets.destroy', $budget) }}" data-confirm="¿Eliminar este presupuesto?">
                @csrf @method('DELETE')
                <button class="text-link danger-text">Eliminar</button>
            </form>
        </div>
    @endif
</article>
