@props(['transactions', 'actions' => false])
@if($transactions->isNotEmpty())
    <div class="table-scroll">
        <table>
            <thead><tr><th scope="col">Movimiento</th><th scope="col">Tipo</th><th scope="col">Fecha</th><th scope="col" class="number">Monto</th>@if($actions)<th scope="col" class="number">Acciones</th>@endif</tr></thead>
            <tbody>
                @foreach($transactions as $item)
                    @php
                        $isIncome = $item->category->type === 'income';
                        $label = $item->category_detail ?? $item->category->name;
                    @endphp
                    <tr>
                        <th scope="row">
                            <span class="transaction-name">{{ $label }}</span>
                            @if($item->category_detail !== null)<small class="transaction-notes">{{ $item->category->name }}</small>@endif
                            @if($item->notes)<small class="transaction-notes">{{ $item->notes }}</small>@endif
                        </th>
                        <td><span @class(['badge', 'mint' => $isIncome, 'peach' => !$isIncome])>{{ $item->category->type_label }}</span></td>
                        <td class="nowrap">{{ $item->occurred_at->format('d/m/Y') }} <small class="muted">{{ $item->occurred_at->format('H:i') }}</small></td>
                        <td @class(['number', 'amount', 'positive' => $isIncome])>{{ $isIncome ? '+' : '−' }}{{ \App\Services\Money::format($item->amount_cents) }}</td>
                        @if($actions)
                            <td><div class="row-actions">
                                <a class="text-link" href="{{ route('transactions.edit', $item) }}" aria-label="Editar movimiento de {{ $label }}">Editar</a>
                                <form method="post" action="{{ route('transactions.destroy', $item) }}" data-confirm="¿Eliminar este movimiento?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-link danger-text" aria-label="Eliminar movimiento de {{ $label }}">Eliminar</button>
                                </form>
                            </div></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <x-empty title="No hay movimientos para mostrar" icon="⇄" />
@endif
