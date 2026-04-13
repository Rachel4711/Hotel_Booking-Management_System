<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = $request->string('search')->toString();
        $role = $request->string('role')->toString();

        $users = User::query()
            ->when($role !== '', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Admin.Customer.index', compact('users', 'search', 'role'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('Admin.Customer.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('customers.index')->with('success', 'User created successfully.');
    }

    public function show(User $customer): View
    {
        $this->authorize('view', $customer);

        $customer->load(['bookings' => function ($query) {
            $query->latest();
        }, 'bookings.room']);

        return view('Admin.Customer.show', [
            'customer' => $customer,
        ]);
    }

    public function edit(User $customer): View
    {
        $this->authorize('update', $customer);

        return view('Admin.Customer.edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, User $customer): RedirectResponse
    {
        $this->authorize('update', $customer);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($customer->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $customer->name = $validated['name'];
        $customer->phone = $validated['phone'] ?? null;
        $customer->email = $validated['email'];
        $customer->role = $validated['role'];

        if (! empty($validated['password'])) {
            $customer->password = Hash::make($validated['password']);
        }

        $customer->save();

        return redirect()->route('customers.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'User deleted successfully.');
    }
}
