<?php

namespace App\Http\Controllers;

use App\Services\FinancialSummary;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController
{
    public function __invoke(Request $request, FinancialSummary $summary): View
    {
        $user = $request->user();
        $month = $summary->month($request->query('month'));

        return view('dashboard', [
            'month' => $month,
            'totals' => $summary->totals($user, $month),
            'flow' => $summary->flow($user, $month),
            'budgets' => $summary->budgets($user, $month)->take(3),
            'goals' => $user->goals()->where('currency', $user->currency)
                ->withSum('contributions as saved_cents', 'amount_cents')->orderBy('due_on')->orderBy('id')->limit(3)->get(),
            'transactions' => $user->transactions()->where('currency', $user->currency)
                ->where('occurred_at', '>=', $month)->where('occurred_at', '<', $month->addMonth())
                ->with('category')->latest('occurred_at')->orderByDesc('id')->limit(5)->get(),
        ]);
    }
}
