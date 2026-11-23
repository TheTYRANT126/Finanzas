@extends('layout')
@section('title', $goal->name)
@section('section', 'Metas de ahorro')
@section('content')
    <a class="text-link back-link" href="{{ route('goals.index') }}">Todas las metas</a>
    <x-heading :title="$goal->name" :description="'Fecha límite · '.$goal->due_on->format('d/m/Y')">
        <a href="{{ route('goals.edit', $goal) }}" class="button secondary">Editar meta</a>
    </x-heading>
    <div class="two-panels goals-detail">
        <section class="panel">
            <x-goal-progress :goal="$goal" />
            <p class="muted">Faltan {{ \App\Services\Money::format(max(0, $goal->target_cents - ($goal->saved_cents ?? 0)), $goal->currency) }} para llegar al objetivo.</p>
            <form method="post" action="{{ route('goals.destroy', $goal) }}" data-confirm="¿Eliminar la meta y todos sus abonos?">
                @csrf @method('DELETE')
                <button type="submit" class="text-link danger-text">Eliminar meta</button>
            </form>
        </section>
        <section class="panel">
            <div class="panel-heading"><div><h2>Registrar un abono</h2><p>Asigna parte de tu dinero a esta meta. El abono actualiza el progreso de la meta y conserva el saldo de tus movimientos.</p></div></div>
            <x-errors />
            <form method="post" action="{{ route('goals.contributions.store', $goal) }}" class="fields">
                @csrf
                <div class="form-grid">
                    <div class="field"><label for="amount">Monto <small>{{ $goal->currency }}</small></label>
                        <input type="number" id="amount" name="amount" inputmode="decimal" min="0.01" max="99999999.99" step="0.01" value="{{ old('amount') }}" required>
                    </div>
                    <div class="field"><label for="contributed_on">Fecha del abono</label>
                        <input type="date" id="contributed_on" name="contributed_on" min="1900-01-01" max="{{ today()->format('Y-m-d') }}" value="{{ old('contributed_on', today()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <label for="notes">Notas <small>opcional</small></label>
                <input id="notes" name="notes" value="{{ old('notes') }}" maxlength="250">
                <button type="submit" class="button primary">Registrar abono</button>
            </form>
        </section>
    </div>
    <section class="panel movements-panel">
        <div class="panel-heading"><h2>Historial de abonos</h2></div>
        @if($contributions->isNotEmpty())
            <div class="table-scroll">
                <table>
                    <thead><tr><th scope="col">Fecha</th><th scope="col">Notas</th><th scope="col" class="number">Monto</th><th scope="col" class="number">Acciones</th></tr></thead>
                    <tbody>
                        @foreach($contributions as $contribution)
                            <tr>
                                <th scope="row">{{ $contribution->contributed_on->format('d/m/Y') }}</th>
                                <td class="transaction-notes">{{ $contribution->notes ?: '—' }}</td>
                                <td class="number amount positive">{{ \App\Services\Money::format($contribution->amount_cents) }}</td>
                                <td class="number"><form method="post" action="{{ route('goals.contributions.destroy', [$goal, $contribution]) }}" data-confirm="¿Eliminar este abono?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-link danger-text">Eliminar</button>
                                </form></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-pagination :paginator="$contributions" />
        @else
            <x-empty title="Aún no hay abonos para esta meta" icon="◎" />
        @endif
    </section>
@endsection
