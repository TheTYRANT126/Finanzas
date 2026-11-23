<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use App\Services\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContributionController
{
    public function store(Request $request, SavingsGoal $goal): RedirectResponse
    {
        Gate::authorize('update', $goal);
        $data = $request->validate([
            'amount' => Money::RULES,
            'contributed_on' => ['required', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:250'],
        ]);

        $goal->contributions()->create([
            'amount_cents' => Money::cents($data['amount']),
            'contributed_on' => $data['contributed_on'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('goals.show', $goal)->with('status', 'Abono registrado.');
    }

    public function destroy(SavingsGoal $goal, int $contribution): RedirectResponse
    {
        Gate::authorize('update', $goal);
        $goal->contributions()->findOrFail($contribution)->delete();

        return redirect()->route('goals.show', $goal)->with('status', 'Abono eliminado.');
    }
}
