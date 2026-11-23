@extends('layout')
@section('title', 'Crear cuenta')
@section('guest')
    <x-auth-shell title="Crear cuenta" description="Elige la moneda con la que vas a comenzar.">
        <form action="{{ route('register.store') }}" method="post" class="fields">
            @csrf
            <label for="name">Nombre</label>
            <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name" maxlength="100">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" maxlength="255">
            <label for="currency">Moneda</label>
            <select id="currency" name="currency" required>
                @foreach(\App\Services\Money::CURRENCIES as $code => $name)
                    <option value="{{ $code }}" @selected(old('currency', 'MXN') === $code)>{{ $code }} · {{ $name }}</option>
                @endforeach
            </select>
            <label for="password">Contraseña <small>mínimo 8 caracteres</small></label>
            <input type="password" id="password" name="password" required minlength="8" maxlength="72" autocomplete="new-password">
            <label for="password_confirmation">Confirma tu contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" maxlength="72" autocomplete="new-password">
            <button type="submit" class="button primary">Crear mi cuenta</button>
        </form>
        <p class="auth-switch">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </x-auth-shell>
@endsection
