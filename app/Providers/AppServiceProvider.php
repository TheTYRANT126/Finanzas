<?php

namespace App\Providers;

use App\Models\Budget;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use App\Policies\FinanceRecordPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

        Gate::policy(Transaction::class, FinanceRecordPolicy::class);
        Gate::policy(Budget::class, FinanceRecordPolicy::class);
        Gate::policy(SavingsGoal::class, FinanceRecordPolicy::class);
    }
}
