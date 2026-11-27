@extends('layout')
@section('title', 'Presupuestos')
@section('section', 'Presupuestos')
@section('content')
    <x-heading title="Presupuestos" :description="'Límites por categoría · '.ucfirst($month->translatedFormat('F \d\e Y'))">
        <x-month-select :month="$month" route="budgets.index" />
        <a class="button primary" href="{{ route('budgets.create', ['month' => $month->format('Y-m')]) }}"><span aria-hidden="true">+</span> Nuevo presupuesto</a>
    </x-heading>
    @if($budgets->isEmpty())
        <section class="panel">
            <x-empty title="Sin presupuestos en este mes" description="Define un límite para una categoría y consulta cuánto has gastado." icon="▤">
                <a class="button secondary" href="{{ route('budgets.create', ['month' => $month->format('Y-m')]) }}">Crear presupuesto</a>
            </x-empty>
        </section>
    @else
        <div class="record-grid">
            @foreach($budgets as $budget)
                <div class="panel"><x-budget-progress :budget="$budget" full /></div>
            @endforeach
        </div>
    @endif
@endsection
