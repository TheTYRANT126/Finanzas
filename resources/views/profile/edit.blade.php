@extends('layout')
@section('title', 'Configuración')
@section('section', 'Configuración')
@section('content')
    <x-heading title="Configuración" description="Administra tu perfil, moneda y acceso." />
    <div class="two-panels profile-grid">
        <section class="panel" id="preferencias">
            <div class="panel-heading"><h2>Datos de la cuenta</h2></div>
            <x-errors bag="profile" />
            <form class="fields" method="post" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
                <label for="name">Nombre</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="100" autocomplete="name" required>
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" required>
                <label for="profile_password">Contraseña actual <small>para cambiar el correo</small></label>
                <input type="password" id="profile_password" name="profile_password" maxlength="72" autocomplete="current-password">
                <label for="currency">Moneda activa</label>
                <select id="currency" name="currency" aria-describedby="currency-help" required>
                    @foreach($currencies as $code => $name)
                        <option value="{{ $code }}" @selected(old('currency', $user->currency) === $code)>{{ $code }} · {{ $name }}</option>
                    @endforeach
                </select>
                <p class="field-help" id="currency-help">Cada moneda tiene sus propios movimientos, presupuestos y metas. También puedes cambiarla desde la barra superior.</p>
                <button type="submit" class="button primary">Guardar perfil</button>
            </form>
        </section>
        <section class="panel">
            <div class="panel-heading"><h2>Cambiar contraseña</h2></div>
            <x-errors bag="password" />
            <form class="fields" method="post" action="{{ route('profile.password') }}">
                @csrf @method('PATCH')
                <label for="current_password">Contraseña actual</label>
                <input id="current_password" name="current_password" type="password" maxlength="72" autocomplete="current-password" required>
                <label for="password">Contraseña nueva <small>mínimo 8 caracteres</small></label>
                <input id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
                <label for="password_confirmation">Confirma la contraseña nueva</label>
                <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
                <button type="submit" class="button secondary">Actualizar contraseña</button>
            </form>
        </section>
        <section class="panel">
            <div class="panel-heading"><h2>Inicios de sesión recientes</h2></div>
            <ul class="login-list">
                @forelse($logins as $login)
                    <li><span>{{ $login->created_at->format('d/m/Y') }}</span><strong>{{ $login->created_at->format('H:i') }}</strong></li>
                @empty
                    <li class="muted">Aún no hay actividad registrada.</li>
                @endforelse
            </ul>
        </section>
        <section class="panel danger-panel">
            <div class="panel-heading"><div><h2>Eliminar cuenta</h2><p>Se eliminarán de forma definitiva tus movimientos, presupuestos, metas y abonos de ambas monedas.</p></div></div>
            <x-errors bag="delete" />
            <form class="fields" method="post" action="{{ route('profile.destroy') }}" data-confirm="¿Eliminar permanentemente tu cuenta y todos sus datos?">
                @csrf @method('DELETE')
                <label for="deletion_password">Contraseña actual</label>
                <input id="deletion_password" name="deletion_password" type="password" maxlength="72" autocomplete="current-password" required>
                <label class="checkbox"><input name="confirm_delete" type="checkbox" value="1" required> Confirmo que quiero eliminar la cuenta.</label>
                <button type="submit" class="button danger-button">Eliminar mi cuenta</button>
            </form>
        </section>
    </div>
@endsection
