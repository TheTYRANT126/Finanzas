<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['category_id', 'category_detail', 'currency', 'amount_cents', 'occurred_at', 'notes'];

    protected function casts(): array
    {
        return ['occurred_at' => 'datetime', 'amount_cents' => 'integer'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
