<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name', 60);
            $table->enum('type', ['income', 'expense']);
        });

        DB::table('categories')->insert([
            ['slug' => 'salary', 'name' => 'Salario', 'type' => 'income'],
            ['slug' => 'freelance', 'name' => 'Freelance', 'type' => 'income'],
            ['slug' => 'investment', 'name' => 'Inversiones', 'type' => 'income'],
            ['slug' => 'other-income', 'name' => 'Otros ingresos', 'type' => 'income'],
            ['slug' => 'food', 'name' => 'Alimentación', 'type' => 'expense'],
            ['slug' => 'transport', 'name' => 'Transporte', 'type' => 'expense'],
            ['slug' => 'housing', 'name' => 'Vivienda', 'type' => 'expense'],
            ['slug' => 'services', 'name' => 'Servicios', 'type' => 'expense'],
            ['slug' => 'health', 'name' => 'Salud', 'type' => 'expense'],
            ['slug' => 'education', 'name' => 'Educación', 'type' => 'expense'],
            ['slug' => 'leisure', 'name' => 'Entretenimiento', 'type' => 'expense'],
            ['slug' => 'shopping', 'name' => 'Compras', 'type' => 'expense'],
            ['slug' => 'other-expense', 'name' => 'Otros gastos', 'type' => 'expense'],
        ]);

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->enum('currency', ['MXN', 'USD']);
            $table->unsignedBigInteger('amount_cents');
            $table->dateTime('occurred_at');
            $table->string('notes', 500)->nullable();
            $table->timestamps();
            $table->index(['user_id', 'currency', 'occurred_at']);
            $table->index(['user_id', 'currency', 'category_id', 'occurred_at'], 'transactions_category_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('categories');
    }
};
