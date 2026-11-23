<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoalRequest;
use App\Models\SavingsGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavingsGoalController
{
    public function index(Request $request): View
    {
        return view('goals.index', [
            'goals' => $request->user()->goals()->where('currency', $request->user()->currency)
                ->withSum('contributions as saved_cents', 'amount_cents')
                ->orderBy('due_on')->orderBy('id')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('goals.form', ['goal' => new SavingsGoal()]);
    }

    public function store(GoalRequest $request): RedirectResponse
    {
        $goal = $request->user()->goals()->create([
            ...$request->attributesForModel(),
            'currency' => $request->user()->currency,
        ]);

        return redirect()->route('goals.show', $goal)->with('status', 'Meta creada.');
    }

    public function show(SavingsGoal $goal): View
    {
        $goal->loadSum('contributions as saved_cents', 'amount_cents');

        return view('goals.show', [
            'goal' => $goal,
            'contributions' => $goal->contributions()->latest('contributed_on')->orderByDesc('id')->paginate(12),
        ]);
    }

    public function edit(SavingsGoal $goal): View
    {
        return view('goals.form', ['goal' => $goal]);
    }

    public function update(GoalRequest $request, SavingsGoal $goal): RedirectResponse
    {
        $goal->update($request->attributesForModel());

        return redirect()->route('goals.show', $goal)->with('status', 'Meta actualizada.');
    }

    public function destroy(SavingsGoal $goal): RedirectResponse
    {
        $goal->delete();

        return redirect()->route('goals.index')->with('status', 'Meta y abonos eliminados.');
    }
}
