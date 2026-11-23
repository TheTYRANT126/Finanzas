<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use App\Models\Category;
use App\Services\FinancialSummary;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BudgetController
{
    public function index(Request $request, FinancialSummary $summary): View
    {
        $month = $summary->month($request->query('month'));
        $user = $request->user();

        return view('budgets.index', [
            'month' => $month,
            'budgets' => $summary->budgets($user, $month),
            'spending' => $summary->totals($user, $month)['categories']->pluck('total', 'id'),
        ]);
    }

    public function create(Request $request, FinancialSummary $summary): View
    {
        return $this->form(new Budget(['month' => $summary->month($request->query('month'))]));
    }

    public function edit(Budget $budget): View
    {
        return $this->form($budget);
    }

    public function store(BudgetRequest $request): RedirectResponse
    {
        try {
            $budget = $request->user()->budgets()->create([
                ...$request->attributesForModel(),
                'currency' => $request->user()->currency,
            ]);
        } catch (UniqueConstraintViolationException) {
            $this->duplicate();
        }

        return redirect()->route('budgets.index', ['month' => $budget->month->format('Y-m')])
            ->with('status', 'Presupuesto creado.');
    }

    public function update(BudgetRequest $request, Budget $budget): RedirectResponse
    {

        try {
            $budget->update($request->attributesForModel());
        } catch (UniqueConstraintViolationException) {
            $this->duplicate();
        }

        return redirect()->route('budgets.index', ['month' => $budget->month->format('Y-m')])
            ->with('status', 'Presupuesto actualizado.');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        $budget->delete();

        return redirect()->route('budgets.index', ['month' => $budget->month->format('Y-m')])
            ->with('status', 'Presupuesto eliminado.');
    }

    private function form(Budget $budget): View
    {
        return view('budgets.form', [
            'budget' => $budget,
            'categories' => Category::where('type', 'expense')->orderBy('name')->get(),
        ]);
    }

    private function duplicate(): never
    {
        throw ValidationException::withMessages(['category_id' => 'Ya existe un presupuesto para esta categoría en el mes seleccionado.']);
    }
}
