@extends('layout')
@php
    $editing = $transaction->exists;
    $title = $editing ? 'Editar movimiento' : 'Nuevo movimiento';
@endphp
@section('title', $title)
@section('section', 'Movimientos')
@section('content')
    <x-heading :title="$title" />
    <section class="panel form-panel">
        <x-errors />
        <form class="fields" method="post" action="{{ $editing ? route('transactions.update', $transaction) : route('transactions.store') }}" data-category-form>
            @csrf
            @if($editing) @method('PATCH') @endif
            <input type="hidden" name="currency" value="{{ auth()->user()->currency }}">
            <div class="form-grid">
                <div class="field"><label for="type">Tipo</label>
                    <select id="type" name="type" required data-category-type>
                        @foreach(\App\Models\Category::TYPES as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $transaction->category?->type ?? 'expense') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field"><label for="category_id">Categoría</label>
                    <select id="category_id" name="category_id" required data-category-list>
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories->groupBy('type') as $type => $options)
                            <optgroup data-type="{{ $type }}" label="{{ \App\Models\Category::TYPES[$type] }}">
                                @foreach($options as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $transaction->category_id) == $category->id) @if($category->allowsCustomDetail()) data-custom-category @endif>{{ $category->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <x-category-detail :value="$transaction->category_detail" placeholder="Ej. Regalos o venta de segunda mano" />
                <div class="field"><label for="amount">Monto <small>{{ auth()->user()->currency }}</small></label>
                    <input id="amount" name="amount" type="number" inputmode="decimal" step="0.01" min="0.01" max="99999999.99" value="{{ old('amount', \App\Services\Money::input($transaction->amount_cents)) }}" required>
                </div>
                <div class="field"><label for="occurred_at">Fecha y hora</label>
                    <input id="occurred_at" name="occurred_at" type="datetime-local" min="1900-01-01T00:00" max="2099-12-31T23:59" value="{{ old('occurred_at', ($transaction->occurred_at ?? now())->format('Y-m-d\TH:i')) }}" required>
                </div>
            </div>
            <label for="notes">Notas <small>opcional</small></label>
            <textarea id="notes" name="notes" maxlength="500" rows="4">{{ old('notes', $transaction->notes) }}</textarea>
            <div class="form-actions">
                <button type="submit" class="button primary">{{ $editing ? 'Guardar cambios' : 'Registrar movimiento' }}</button>
                <a class="button secondary" href="{{ route('transactions.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
