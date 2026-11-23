@props(['goal', 'linked' => false])
@php
    $saved = $goal->saved_cents ?? 0;
    $tone = match($goal->status) {
        'Plazo vencido' => 'warning',
        'Completada' => 'good',
        default => 'neutral',
    };
@endphp
<article class="progress-item">
    <div class="progress-head">
        <h3>
            @if($linked)<a href="{{ route('goals.show', $goal) }}">{{ $goal->name }}</a>@else Capital acumulado @endif
        </h3>
        <span class="badge {{ $tone }}">{{ $goal->status }}</span>
    </div>
    <progress class="progress {{ $tone }}" value="{{ min($saved, $goal->target_cents) }}" max="{{ $goal->target_cents }}" aria-label="Avance de {{ $goal->name }}"></progress>
    <div class="progress-caption">
        <span><strong>{{ \App\Services\Money::format($saved) }}</strong> de {{ \App\Services\Money::format($goal->target_cents) }}</span>
        <span>{{ $goal->progress() }}%</span>
    </div>
</article>
