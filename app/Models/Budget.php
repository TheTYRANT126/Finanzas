<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = ['category_id', 'currency', 'month', 'limit_cents'];

    protected function casts(): array
    {
        return ['month' => 'date', 'limit_cents' => 'integer'];
    }

    protected function month(): Attribute
    {
        return Attribute::make(set: fn ($value) => CarbonImmutable::parse($value)->startOfMonth()->toDateString());
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function consumption(int $spent): float
    {
        return round($spent / $this->limit_cents * 100, 1);
    }
}
