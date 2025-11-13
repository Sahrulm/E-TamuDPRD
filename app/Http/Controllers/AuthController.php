<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     // Tamu boleh lihat form & login; yang sudah login diarahkan.
    //     $this->middleware('guest')->only(['showLogin', 'login']);
    //     // Logout hanya untuk yang sudah login.
    //     $this->middleware('auth')->only(['logout']);
    // }

    public function showLogin(): ViewContract|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectToRole(Auth::user());
        }

        // sesuaikan view kamu: resources/views/auth/login.blade.php
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Validasi lebih dulu
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Kunci rate limit: email (lowercase) + IP
        $email = Str::lower($credentials['email']);
        $key   = "login:{$email}|{$request->ip()}";

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            return $this->redirectToRole(Auth::user());
        }

        // Tambah 1 hit & cooldown 60 detik
        RateLimiter::hit($key, 60);

        throw ValidationException::withMessages([
            'email' => 'Kredensial tidak valid.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // sesuaikan route landing
        return redirect('/login');
    }

    protected function redirectToRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin'        => redirect()->route('admin.index'),
            'host'         => redirect()->route('host.index'),
            'resepsionis'  => redirect()->route('resepsionis.index'),
            default        => redirect()->route('welcome'),
        };
    }
}
