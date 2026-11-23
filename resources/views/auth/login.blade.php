@extends('layout')
@section('title', 'Iniciar sesión')
@section('guest')
    <x-auth-shell title="Iniciar sesión" description="Ingresa a tu espacio de finanzas personales.">
        <form action="{{ route('login.store') }}" method="post" class="fields">
            @csrf
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" maxlength="255" @error('email') aria-invalid="true" @enderror>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required autocomplete="current-password" maxlength="72" @error('password') aria-invalid="true" @enderror>
            <button type="submit" class="button primary">Entrar</button>
        </form>
        <p class="auth-switch">¿Es tu primera vez? <a href="{{ route('register') }}">Crea una cuenta</a></p>
    </x-auth-shell>
@endsection
