<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login', [
            'loginType' => 'user',
            'submitRoute' => 'login.submit',
        ]);
    }

    public function showAdminLogin(): View
    {
        return view('auth.login', [
            'loginType' => 'admin',
            'submitRoute' => 'adminLogin.submit',
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Invalid email and password. Please register if you do not have an account.',
            ])->onlyInput('email');
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if (Auth::user()?->isAdmin()) {
            return redirect()->route('dashboard')->with('success', 'Welcome back, admin.');
        }

        return redirect()->route('user.profile.edit')->with('success', 'Welcome back.');
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid admin email or password.',
            ])->onlyInput('email');
        }

        if (! $user->isAdmin()) {
            return back()->withErrors([
                'email' => 'This account is not an admin account.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Welcome back, admin.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // First validate basic fields
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $password = $request->input('password');
        $passwordConfirm = $request->input('password_confirmation');

        // Check if passwords match
        if ($password !== $passwordConfirm) {
            return back()->withErrors([
                'password_confirmation' => 'Passwords do not match.',
            ])->onlyInput('name', 'email', 'phone');
        }

        // Validate password requirements
        $passwordErrors = [];

        if (strlen($password) < 8) {
            $passwordErrors[] = 'Password must be at least 8 characters.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $passwordErrors[] = 'Password must contain at least one lowercase letter (a-z).';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $passwordErrors[] = 'Password must contain at least one uppercase letter (A-Z).';
        }
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'"<>,.?\\|`~]/', $password)) {
            $passwordErrors[] = 'Password must contain at least one special character (!@#$%^&* etc).';
        }

        if (!empty($passwordErrors)) {
            return back()->withErrors([
                'password' => 'Password must be at least 8 characters, at least one uppercase letter (A-Z), at least one special character (!@#$%^&* etc).',
            ])->onlyInput('name', 'email', 'phone');
        }

        $validated = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $password,
        ];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.profile.edit')->with('success', 'Account created successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have logged out successfully.');
    }
}
