<?php

namespace App\Http\Controllers;

use App\Services\Money;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'currencies' => Money::CURRENCIES,
            'logins' => $request->user()->loginRecords()->latest()->limit(8)->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (is_string($request->input('email'))) {
            $request->merge(['email' => mb_strtolower(trim($request->input('email')))]);
        }

        $data = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'currency' => ['required', Rule::in(array_keys(Money::CURRENCIES))],
        ]);

        if ($data['email'] !== $user->email) {
            $request->validateWithBag('profile', ['profile_password' => ['required', 'current_password']]);
        }

        try {
            $user->update($data);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['email' => 'Ya existe una cuenta con este correo.'])->errorBag('profile');
        }

        return back()->with('status', 'Perfil actualizado.');
    }

    public function password(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'max:72', 'regex:/\A[^\x00]{1,72}\z/', 'confirmed', 'different:current_password', Password::min(8)],
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);
        $request->session()->regenerate(true);

        return back()->with('status', 'Contraseña actualizada.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('delete', [
            'deletion_password' => ['required', 'current_password'],
            'confirm_delete' => ['accepted'],
        ]);

        $request->user()->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Tu cuenta fue eliminada.');
    }
}
