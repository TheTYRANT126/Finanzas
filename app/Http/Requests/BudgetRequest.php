<?php

namespace App\Http\Requests;

use App\Services\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BudgetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('type', 'expense')],
            'month' => ['required', 'date_format:Y-m', 'after_or_equal:1900-01', 'before:2100-01'],
            'limit' => Money::RULES,
            'currency' => ['required', Rule::in([$this->user()->currency])],
        ];
    }

    public function attributesForModel(): array
    {
        return [
            'category_id' => $this->validated('category_id'),
            'month' => $this->validated('month').'-01',
            'limit_cents' => Money::cents($this->validated('limit')),
        ];
    }
}
