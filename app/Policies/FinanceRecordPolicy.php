<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use App\Models\User;

class FinanceRecordPolicy
{
    public function view(User $user, Budget|SavingsGoal|Transaction $record): bool
    {
        return $user->id === (int) $record->user_id && $user->currency === $record->currency;
    }

    public function update(User $user, Budget|SavingsGoal|Transaction $record): bool
    {
        return $this->view($user, $record);
    }

    public function delete(User $user, Budget|SavingsGoal|Transaction $record): bool
    {
        return $this->view($user, $record);
    }
}
