<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();
        $this->authorize('update', $user);

        return view('User.profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('users', 'public');
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        return redirect()->route('user.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
