<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Services\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_keys(Category::TYPES))],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')
                ->where('type', is_string($this->input('type')) ? $this->input('type') : '')],
            'amount' => Money::RULES,
            'occurred_at' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:1900-01-01', 'before:2100-01-01'],
            'notes' => ['nullable', 'string', 'max:500'],
            'currency' => ['required', Rule::in([$this->user()->currency])],
        ];
    }

    public function attributesForModel(): array
    {
        return [
            ...$this->safe()->only(['category_id', 'occurred_at', 'notes']),
            'amount_cents' => Money::cents($this->validated('amount')),
        ];
    }
}
