<?php

namespace App\Services;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FinancialSummary
{
    private const CHART_COLORS = ['#3b8772', '#e49c82', '#7e7aba', '#c4a052', '#668bab', '#ae6e83', '#648152', '#a88167', '#68728e'];

    public function month(mixed $value): CarbonImmutable
    {
        if (is_string($value) && preg_match('/\A(?:19|20)\d{2}-(?:0[1-9]|1[0-2])\z/', $value)) {
            return CarbonImmutable::createFromFormat('!Y-m', $value);
        }

        return CarbonImmutable::now()->startOfMonth();
    }

    public function totals(User $user, CarbonImmutable $month): array
    {
        $rows = DB::table('transactions')
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->where('transactions.user_id', $user->id)
            ->where('transactions.currency', $user->currency)
            ->where('transactions.occurred_at', '>=', $month)
            ->where('transactions.occurred_at', '<', $month->addMonth())
            ->groupBy('categories.id', 'categories.name', 'categories.type')
            ->selectRaw('categories.id, categories.name, categories.type, SUM(transactions.amount_cents) as total')
            ->orderByDesc('total')
            ->orderBy('categories.id')
            ->get();

        $income = (int) $rows->where('type', 'income')->sum('total');
        $expenses = $rows->where('type', 'expense')->values();
        $expense = (int) $expenses->sum('total');

        return [
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
            'categories' => $expenses->map(function ($row, int $index) use ($expense): array {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'total' => (int) $row->total,
                    'percent' => (int) round($row->total / $expense * 100),
                    'color' => self::CHART_COLORS[$index % count(self::CHART_COLORS)],
                ];
            }),
        ];
    }

    public function flow(User $user, CarbonImmutable $month): array
    {
        $days = DB::table('transactions')
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->where('transactions.user_id', $user->id)
            ->where('transactions.currency', $user->currency)
            ->where('transactions.occurred_at', '>=', $month)
            ->where('transactions.occurred_at', '<', $month->addMonth())
            ->groupByRaw('DATE(transactions.occurred_at), categories.type')
            ->selectRaw('DATE(transactions.occurred_at) as day, categories.type, SUM(transactions.amount_cents) as total')
            ->get()
            ->groupBy('day');

        $income = $expense = 0;
        $flow = [];
        for ($day = 1; $day <= $month->daysInMonth; $day++) {
            $date = $month->day($day);
            $totals = $days->get($date->toDateString(), collect())->pluck('total', 'type');
            $income += (int) ($totals['income'] ?? 0);
            $expense += (int) ($totals['expense'] ?? 0);
            $flow[] = ['day' => $day, 'income' => $income, 'expense' => $expense];
        }

        return $flow;
    }

    public function budgets(User $user, CarbonImmutable $month): Collection
    {
        return $user->budgets()->where('currency', $user->currency)
            ->where('month', $month->toDateString())->with('category')->orderBy('id')->get();
    }
}
