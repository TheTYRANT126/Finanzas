@props(['currency'])
@php
    $section = \Illuminate\Support\Str::before(request()->route()?->getName() ?? '', '.');
    $month = request()->query('month');
@endphp
<details class="currency-menu">
    <summary class="currency-chip" aria-label="Cambiar moneda. Ahora en {{ $currency }}">
        {{ $currency }}
    </summary>
    <form method="post" action="{{ route('profile.currency') }}" class="currency-options" aria-label="Moneda activa">
        @csrf @method('PATCH')
        <input type="hidden" name="section" value="{{ $section }}">
        @if(is_string($month) && preg_match('/\A(?:19|20)\d{2}-(?:0[1-9]|1[0-2])\z/', $month))
            <input type="hidden" name="month" value="{{ $month }}">
        @endif
        @foreach(\App\Services\Money::CURRENCIES as $code => $label)
            <button type="submit" name="currency" value="{{ $code }}" @if($code === $currency) disabled @endif>
                <strong>{{ $code }}</strong><span>{{ $label }}</span>
                @if($code === $currency)<span class="visually-hidden">Moneda activa</span>@endif
            </button>
        @endforeach
    </form>
</details>
