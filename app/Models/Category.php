<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const TYPES = ['income' => 'Ingreso', 'expense' => 'Gasto'];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type];
    }
}
