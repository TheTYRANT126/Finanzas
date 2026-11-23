<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', Rule::in(array_keys(Category::TYPES))],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'from' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before:2100-01-01'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before:2100-01-01',
                ...($request->filled('from') ? ['after_or_equal:from'] : [])],
        ]);

        $filters = $validator->fails() ? [] : $validator->validated();

        $transactions = $request->user()->transactions()->where('currency', $request->user()->currency)
            ->with('category')
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->whereHas('category', fn ($category) => $category->where('type', $type)))
            ->when($filters['category_id'] ?? null, fn ($query, $id) => $query->where('category_id', $id))
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->where('occurred_at', '>=', $from.' 00:00:00'))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->where('occurred_at', '<', CarbonImmutable::parse($to)->addDay()->startOfDay()));

        $search = $filters['q'] ?? '';
        if ($search !== '') {
            $transactions->where(fn (Builder $query) => $query->where('notes', 'like', '%'.$search.'%')
                ->orWhereHas('category', fn ($category) => $category->where('name', 'like', '%'.$search.'%')));
        }

        return view('transactions.index', [
            'transactions' => $transactions->orderByDesc('occurred_at')->orderByDesc('id')->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $filters,
            'filterErrors' => $validator->errors(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Transaction());
    }

    public function edit(Transaction $transaction): View
    {
        return $this->form($transaction);
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        $request->user()->transactions()->create([
            ...$request->attributesForModel(),
            'currency' => $request->user()->currency,
        ]);

        return redirect()->route('transactions.index')->with('status', 'Movimiento registrado.');
    }

    public function update(TransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($request->attributesForModel());

        return redirect()->route('transactions.index')->with('status', 'Movimiento actualizado.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return back()->with('status', 'Movimiento eliminado.');
    }

    private function form(Transaction $transaction): View
    {
        return view('transactions.form', [
            'transaction' => $transaction,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
