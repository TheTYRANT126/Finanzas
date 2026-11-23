@extends('layout')
@section('title', 'Metas de ahorro')
@section('section', 'Metas de ahorro')
@section('content')
    <x-heading title="Metas de ahorro" description="Organiza tus objetivos y consulta el avance de cada uno.">
        <a class="button primary" href="{{ route('goals.create') }}"><span aria-hidden="true">+</span> Nueva meta</a>
    </x-heading>
    @if($goals->isEmpty())
        <section class="panel">
            <x-empty title="¿Para qué quieres ahorrar?" description="Crea una meta con un monto objetivo y registra tus abonos." icon="◎">
                <a class="button secondary" href="{{ route('goals.create') }}">Crear mi primera meta</a>
            </x-empty>
        </section>
    @else
        <div class="record-grid">
            @foreach($goals as $goal)
                <div class="panel">
                    <x-goal-progress :goal="$goal" linked />
                    <div class="card-actions"><span class="muted">Hasta el {{ $goal->due_on->format('d/m/Y') }}</span><a class="text-link" href="{{ route('goals.show', $goal) }}">Ver meta</a></div>
                </div>
            @endforeach
        </div>
        <x-pagination :paginator="$goals" />
    @endif
@endsection
