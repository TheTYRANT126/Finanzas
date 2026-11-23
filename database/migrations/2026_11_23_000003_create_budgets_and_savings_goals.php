<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->enum('currency', ['MXN', 'USD']);
            $table->date('month');
            $table->unsignedBigInteger('limit_cents');
            $table->timestamps();
            $table->unique(['user_id', 'currency', 'month', 'category_id'], 'budgets_month_category');
        });

        Schema::create('savings_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('currency', ['MXN', 'USD']);
            $table->string('name', 100);
            $table->unsignedBigInteger('target_cents');
            $table->date('due_on');
            $table->timestamps();
            $table->index(['user_id', 'currency', 'due_on']);
        });

        Schema::create('goal_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_goal_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount_cents');
            $table->date('contributed_on');
            $table->string('notes', 250)->nullable();
            $table->timestamps();
            $table->index(['savings_goal_id', 'contributed_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_contributions');
        Schema::dropIfExists('savings_goals');
        Schema::dropIfExists('budgets');
    }
};
