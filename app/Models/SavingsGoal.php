<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavingsGoal extends Model
{
    protected $fillable = ['currency', 'name', 'target_cents', 'due_on'];

    protected function casts(): array
    {
        return ['target_cents' => 'integer', 'due_on' => 'date', 'saved_cents' => 'integer'];
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(GoalContribution::class);
    }

    public function progress(): float
    {
        return round(($this->saved_cents ?? 0) / $this->target_cents * 100, 1);
    }

    public function getStatusAttribute(): string
    {
        if ($this->saved_cents >= $this->target_cents) {
            return 'Completada';
        }

        return $this->due_on->lt(today()) ? 'Plazo vencido' : 'En progreso';
    }
}
