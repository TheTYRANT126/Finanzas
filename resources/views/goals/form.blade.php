@extends('layout')
@php($title = $goal->exists ? 'Editar meta' : 'Nueva meta de ahorro')
@section('title', $title)
@section('section', 'Metas de ahorro')
@section('content')
    <x-heading :title="$title" />
    <section class="panel form-panel">
        <x-errors />
        <form class="fields" method="post" action="{{ $goal->exists ? route('goals.update', $goal) : route('goals.store') }}">
            @csrf
            @if($goal->exists) @method('PATCH') @endif
            <input type="hidden" name="currency" value="{{ auth()->user()->currency }}">
            <label for="name">Nombre de la meta</label>
            <input id="name" name="name" maxlength="100" value="{{ old('name', $goal->name) }}" placeholder="Ej. Fondo de emergencia" required>
            <div class="form-grid">
                <div class="field"><label for="target">Monto objetivo <small>{{ auth()->user()->currency }}</small></label>
                    <input type="number" id="target" name="target" inputmode="decimal" min="0.01" max="99999999.99" step="0.01" value="{{ old('target', \App\Services\Money::input($goal->target_cents)) }}" required>
                </div>
                <div class="field"><label for="due_on">Fecha límite</label>
                    <input type="date" id="due_on" name="due_on" min="1900-01-01" max="2099-12-31" value="{{ old('due_on', $goal->due_on?->format('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="button primary">{{ $goal->exists ? 'Guardar cambios' : 'Crear meta' }}</button>
                <a class="button secondary" href="{{ $goal->exists ? route('goals.show', $goal) : route('goals.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
