<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $admin = auth()->user();
        $this->authorize('update', $admin);

        return view('Admin.profile', [
            'admin' => $admin,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = auth()->user();
        $this->authorize('update', $admin);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $admin->name = $validated['name'];
        $admin->phone = $validated['phone'] ?? null;

        if ($request->hasFile('photo')) {
            if ($admin->photo) {
                Storage::disk('public')->delete($admin->photo);
            }
            $admin->photo = $request->file('photo')->store('users', 'public');
        }

        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Admin profile updated successfully.');
    }
}
