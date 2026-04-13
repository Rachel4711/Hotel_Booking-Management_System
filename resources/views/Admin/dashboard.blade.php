@extends('Layouts.admin')

@section('content')

@php
    $isFiltered = ($search ?? '') !== '' || ($role ?? '') !== '';
@endphp

<h1>Admin Dashboard</h1>

@if(session('success'))
    <div class="notice success">{{ session('success') }}</div>
@endif

<div class="dashboard-stats" style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:12px; margin:12px 0 16px;">
        <div class="card" style="padding:12px;">
            <h3 style="margin:0; font-size:16px;">Total Customers</h3>
            <p style="margin:6px 0 0; font-size:24px; font-weight:700;">{{ $totalCustomers }}</p>
        </div>
        <div class="card" style="padding:12px;">
            <h3 style="margin:0; font-size:16px;">Total Hotels</h3>
            <p style="margin:6px 0 0; font-size:24px; font-weight:700;">{{ $totalHotels }}</p>
        </div>
        <div class="card" style="padding:12px;">
            <h3 style="margin:0; font-size:16px;">Total Bookings</h3>
            <p style="margin:6px 0 0; font-size:24px; font-weight:700;">{{ $totalBookings }}</p>
        </div>
</div>

<form id="dashboard-filter-form" method="GET" action="{{ route('dashboard') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
    <input id="dashboard-search" class="input" style="max-width:280px;" type="text" name="search" value="{{ $search }}" placeholder="Search name, phone, email">
    <select id="dashboard-role" class="input" style="max-width:180px;" name="role">
            <option value="">All roles</option>
            <option value="user" {{ $role === 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    <button id="btn-filter" type="submit" class="form-action {{ $isFiltered ? 'primary' : 'secondary' }}">Filter</button>
    <button id="btn-reset" type="button" class="form-action {{ $isFiltered ? 'secondary' : 'primary' }}">Reset</button>
    <a id="btn-add-user" class="form-action secondary" href="{{ route('customers.create') }}">Add User</a>
</form>

<div style="overflow:auto; background:#fff; border:1px solid #dbe3ea; border-radius:10px; padding:4px 12px;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="text-align:left; padding:10px; border-bottom:1px solid #e6edf2;">ID</th>
                    <th style="text-align:left; padding:10px; border-bottom:1px solid #e6edf2;">Name</th>
                    <th style="text-align:left; padding:10px; border-bottom:1px solid #e6edf2;">Email</th>
                    <th style="text-align:left; padding:10px; border-bottom:1px solid #e6edf2;">Phone</th>
                    <th style="text-align:left; padding:10px; border-bottom:1px solid #e6edf2;">Role</th>
                    <th style="text-align:left; padding:10px; border-bottom:1px solid #e6edf2;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td style="padding:10px; border-bottom:1px solid #f1f4f7;">{{ $user->id }}</td>
                        <td style="padding:10px; border-bottom:1px solid #f1f4f7;">{{ $user->name }}</td>
                        <td style="padding:10px; border-bottom:1px solid #f1f4f7;">{{ $user->email }}</td>
                        <td style="padding:10px; border-bottom:1px solid #f1f4f7;">{{ $user->phone ?? '-' }}</td>
                        <td style="padding:10px; border-bottom:1px solid #f1f4f7;">{{ $user->role }}</td>
                        <td style="padding:10px; border-bottom:1px solid #f1f4f7; display:flex; gap:8px; flex-wrap:wrap;">
                            <a class="form-action secondary" href="{{ route('customers.show', $user) }}">View</a>
                            <a class="form-action secondary" href="{{ route('customers.edit', $user) }}">Edit</a>
                            <form method="POST" action="{{ route('customers.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="form-action" style="background:#c0392b; color:#fff; border:1px solid #c0392b;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:10px;">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

</div>
<form method="POST" action="{{ route('logout') }}" style="margin:10px 0 12px;">
@csrf
    <button type="submit" class="form-action" style="background:#7e6552; color:#fff; border:1px solid #7e6552;">Logout</button>
</form>

<div style="margin-top:14px;">
    {{ $users->links() }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('dashboard-filter-form');
    var searchInput = document.getElementById('dashboard-search');
    var roleSelect = document.getElementById('dashboard-role');
    var filterBtn = document.getElementById('btn-filter');
    var resetBtn = document.getElementById('btn-reset');
    var addUserBtn = document.getElementById('btn-add-user');

    function markActive(button) {
        [filterBtn, resetBtn, addUserBtn].forEach(function (btn) {
            if (!btn) return;
            btn.classList.remove('primary');
            btn.classList.add('secondary');
        });
        if (button) {
            button.classList.remove('secondary');
            button.classList.add('primary');
        }
    }

    if (filterBtn) {
        filterBtn.addEventListener('click', function () {
            markActive(filterBtn);
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (roleSelect) roleSelect.value = '';
            markActive(resetBtn);
            form.submit();
        });
    }

    if (addUserBtn) {
        addUserBtn.addEventListener('click', function () {
            markActive(addUserBtn);
        });
    }
});
</script>


@endsection