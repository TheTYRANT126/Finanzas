@extends('layout')
@section('title', 'Inicio')
@section('content')
    <x-heading title="Resumen financiero" :description="'Tu actividad de '.strtolower($month->translatedFormat('F \d\e Y')).'.'">
        <x-month-select :month="$month" route="dashboard" />
        <a class="button primary" href="{{ route('transactions.create') }}"><span aria-hidden="true">+</span> Nuevo movimiento</a>
    </x-heading>
    <x-financial-metrics :totals="$totals" :month="$month" />
    <x-charts :totals="$totals" :flow="$flow" />
    <div class="two-panels">
        <section class="panel">
            <div class="panel-heading">
                <h2>Presupuestos del mes</h2>
                <a class="text-link" href="{{ route('budgets.index', ['month' => $month->format('Y-m')]) }}">Ver presupuestos</a>
            </div>
            @forelse($budgets as $budget)
                <x-budget-progress :budget="$budget" />
            @empty
                <x-empty title="Sin límites para este mes" icon="▤">
                    <a class="text-link" href="{{ route('budgets.create', ['month' => $month->format('Y-m')]) }}">Crear presupuesto</a>
                </x-empty>
            @endforelse
        </section>
        <section class="panel">
            <div class="panel-heading">
                <h2>Metas de ahorro</h2>
                <a class="text-link" href="{{ route('goals.index') }}">Ver metas</a>
            </div>
            @forelse($goals as $goal)
                <x-goal-progress :goal="$goal" linked />
            @empty
                <x-empty title="Aún no tienes metas de ahorro" icon="◎">
                    <a class="text-link" href="{{ route('goals.create') }}">Crear una meta</a>
                </x-empty>
            @endforelse
        </section>
    </div>
    <section class="panel movements-panel">
        <div class="panel-heading">
            <h2>Movimientos recientes</h2>
            <a class="text-link" href="{{ route('transactions.index', ['from' => $month->toDateString(), 'to' => $month->endOfMonth()->toDateString()]) }}">Ver movimientos</a>
        </div>
        <x-transactions-table :transactions="$transactions" />
    </section>
@endsection
