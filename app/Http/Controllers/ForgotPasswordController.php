<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        return redirect()
            ->route('password.reset', ['email' => $request->string('email')->toString()])
            ->with('success', 'Email verified. You can now reset your password.');
    }

    public function showResetForm(Request $request): View
    {
        return view('auth.reset-password', [
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $request->string('email')->toString())->first();

        if (! $user) {
            return back()->withErrors(['email' => 'The selected email address is invalid.'])->onlyInput('email');
        }

        $user->forceFill([
            'password' => Hash::make($request->string('password')->toString()),
            'remember_token' => null,
        ])->save();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully.');
    }
}
