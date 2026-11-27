@extends('layout')
@php($title = $budget->exists ? 'Editar presupuesto' : 'Nuevo presupuesto')
@section('title', $title)
@section('section', 'Presupuestos')
@section('content')
    <x-heading :title="$title" description="Establece el gasto máximo para una categoría en un mes." />
    <section class="panel form-panel">
        <x-errors />
        <form class="fields" method="post" action="{{ $budget->exists ? route('budgets.update', $budget) : route('budgets.store') }}" data-category-form>
            @csrf
            @if($budget->exists) @method('PATCH') @endif
            <input type="hidden" name="currency" value="{{ auth()->user()->currency }}">
            <div class="form-grid">
                <div class="field"><label for="category_id">Categoría de gasto</label>
                    <select id="category_id" name="category_id" required data-category-list>
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $budget->category_id) == $category->id) @if($category->allowsCustomDetail()) data-custom-category @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label for="month">Mes</label>
                    <input type="month" id="month" name="month" min="1900-01" max="2099-12" value="{{ old('month', $budget->month->format('Y-m')) }}" required>
                </div>
                <x-category-detail :value="$budget->category_detail" placeholder="Ej. Reparaciones"
                    hint="Usa el mismo detalle que escribes en tus movimientos. Si lo dejas vacío, el límite cubre toda la categoría." />
            </div>
            <label for="limit">Límite mensual <small>{{ auth()->user()->currency }}</small></label>
            <input type="number" id="limit" name="limit" inputmode="decimal" min="0.01" max="99999999.99" step="0.01" value="{{ old('limit', \App\Services\Money::input($budget->limit_cents)) }}" required>
            <div class="form-actions">
                <button type="submit" class="button primary">{{ $budget->exists ? 'Guardar cambios' : 'Crear presupuesto' }}</button>
                <a class="button secondary" href="{{ route('budgets.index', ['month' => $budget->month->format('Y-m')]) }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
