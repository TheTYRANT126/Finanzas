<?php

namespace App\Http\Requests;

use App\Services\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'target' => Money::RULES,
            'due_on' => ['required', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before:2100-01-01'],
            'currency' => ['required', Rule::in([$this->user()->currency])],
        ];
    }

    public function attributesForModel(): array
    {
        return [
            ...$this->safe()->only(['name', 'due_on']),
            'target_cents' => Money::cents($this->validated('target')),
        ];
    }
}
