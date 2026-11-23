@extends('layout')
@section('title', 'Reportes')
@section('section', 'Reportes')
@section('content')
    <x-heading title="Reportes del mes" :description="ucfirst($month->translatedFormat('F \d\e Y')).' · '.auth()->user()->currency">
        <x-month-select :month="$month" route="reports" />
    </x-heading>
    <x-financial-metrics :totals="$totals" :month="$month" />
    <x-charts :totals="$totals" :flow="$flow" />
    @if($totals['income'] || $totals['expense'])
        <details class="panel report-details">
            <summary>Consultar cifras acumuladas por día</summary>
            <div class="table-scroll">
                <table>
                    <thead><tr><th scope="col">Día</th><th scope="col" class="number">Ingresos</th><th scope="col" class="number">Gastos</th><th scope="col" class="number">Ahorro neto</th></tr></thead>
                    <tbody>
                        @foreach($flow as $daily)
                            <tr>
                                <th scope="row">{{ $month->day($daily['day'])->format('d/m/Y') }}</th>
                                <td class="number">{{ \App\Services\Money::format($daily['income']) }}</td>
                                <td class="number">{{ \App\Services\Money::format($daily['expense']) }}</td>
                                <td class="number">{{ \App\Services\Money::format($daily['income'] - $daily['expense']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    @endif
@endsection
