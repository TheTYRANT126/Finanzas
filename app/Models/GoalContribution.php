<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalContribution extends Model
{
    protected $fillable = ['amount_cents', 'contributed_on', 'notes'];

    protected function casts(): array
    {
        return ['contributed_on' => 'date', 'amount_cents' => 'integer'];
    }
}
