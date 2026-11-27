@extends('layout')
@section('title', 'Movimientos')
@section('section', 'Movimientos')
@section('content')
    <x-heading title="Movimientos" description="Consulta y administra tus ingresos y gastos.">
        <a class="button primary" href="{{ route('transactions.create') }}"><span aria-hidden="true">+</span> Nuevo movimiento</a>
    </x-heading>
    <section class="panel">
        @if($filterErrors->any())
            <div class="notice error-notice" role="alert">
                <strong>Revisa los filtros de búsqueda.</strong>
                <ul>@foreach($filterErrors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form class="filter-grid" method="get" action="{{ route('transactions.index') }}">
            <div class="field"><label for="q">Buscar</label><input type="search" name="q" id="q" maxlength="100" placeholder="Categoría, detalle o notas" value="{{ $filters['q'] ?? '' }}"></div>
            <div class="field"><label for="filter-type">Tipo</label>
                <select id="filter-type" name="type">
                    <option value="">Todos</option>
                    @foreach(\App\Models\Category::TYPES as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label for="filter-category">Categoría</label>
                <select id="filter-category" name="category_id">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label for="from">Desde</label><input type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}" min="1900-01-01" max="2099-12-31"></div>
            <div class="field"><label for="to">Hasta</label><input type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}" min="1900-01-01" max="2099-12-31"></div>
            <div class="filter-actions">
                <button type="submit" class="button secondary">Filtrar</button>
                @if(array_filter($filters))<a class="text-link" href="{{ route('transactions.index') }}">Limpiar</a>@endif
            </div>
        </form>
    </section>
    <section class="panel movements-panel">
        <div class="panel-heading"><h2>Historial</h2><span class="muted">{{ $transactions->total() }} {{ $transactions->total() === 1 ? 'movimiento' : 'movimientos' }}</span></div>
        <x-transactions-table :transactions="$transactions" actions />
        <x-pagination :paginator="$transactions" />
    </section>
@endsection
