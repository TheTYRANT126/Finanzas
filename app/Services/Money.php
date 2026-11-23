<?php

namespace App\Services;

final class Money
{
    public const CURRENCIES = ['MXN' => 'Peso mexicano', 'USD' => 'Dólar estadounidense'];

    public const RULES = ['required', 'regex:/\A[0-9]{1,8}(?:\.[0-9]{1,2})?\z/', 'numeric', 'min:0.01'];

    public static function cents(string $amount): int
    {
        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '');

        return (int) $whole * 100 + (int) str_pad($fraction, 2, '0');
    }

    public static function format(int $cents, ?string $currency = null): string
    {
        $absolute = abs($cents);
        $whole = (string) intdiv($absolute, 100);
        $grouped = preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $whole);
        $fraction = str_pad((string) ($absolute % 100), 2, '0', STR_PAD_LEFT);

        return ($cents < 0 ? '−' : '').'$'.$grouped.'.'.$fraction.($currency ? ' '.$currency : '');
    }

    public static function input(?int $cents): string
    {
        return $cents === null ? '' : intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
}
