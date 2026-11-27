<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const TYPES = ['income' => 'Ingreso', 'expense' => 'Gasto'];

    public const DETAIL_MAX_LENGTH = 80;

    public function allowsCustomDetail(): bool
    {
        return in_array($this->slug, ['other-income', 'other-expense'], true);
    }

    public static function detailFor(int|string $categoryId, ?string $detail): ?string
    {
        return $detail !== null && self::find($categoryId)?->allowsCustomDetail() ? $detail : null;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type];
    }
}
