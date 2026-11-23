<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Money;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function register(Request $request): RedirectResponse
    {
        $this->normalizeEmail($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'currency' => ['required', Rule::in(array_keys(Money::CURRENCIES))],
            'password' => ['required', 'string', 'max:72', 'regex:/\A[^\x00]{1,72}\z/', 'confirmed', Password::min(8)],
        ]);

        try {
            $user = DB::transaction(function () use ($data) {
                $user = User::create($data);
                $user->loginRecords()->create();

                return $user;
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['email' => 'Ya existe una cuenta con este correo.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Tu cuenta está lista.');
    }

    public function login(Request $request): RedirectResponse
    {
        $this->normalizeEmail($request);
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:72'],
        ]);

        if (! Auth::attempt($data)) {
            throw ValidationException::withMessages(['email' => 'El correo o la contraseña son incorrectos.']);
        }

        $request->session()->regenerate();
        $request->user()->loginRecords()->create();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function normalizeEmail(Request $request): void
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => mb_strtolower(trim($request->input('email')))]);
        }
    }
}
