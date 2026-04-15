@extends('Layouts.admin')
@section('content')

@php
	$isFiltered = ($search ?? '') !== '' || ($role ?? '') !== '';
@endphp

<h1>Customer List</h1>

{{-- @if(session('success'))
	<div class="notice success">{{ session('success') }}</div>
@endif --}}

<div class="panel" style="margin-top:12px; background:#fff; border:1px solid #dbe3ea; border-radius:10px; padding:16px;">
	<form id="customers-filter-form" method="GET" action="{{ route('customers.index') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
		<input id="customers-search" class="input" style="max-width:280px;" type="text" name="search" value="{{ $search }}" placeholder="Search name, phone, email">
		<select id="customers-role" class="input" style="max-width:180px;" name="role">
			<option value="">All roles</option>
			<option value="user" {{ $role === 'user' ? 'selected' : '' }}>User</option>
			<option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
		</select>
		<button id="btn-customers-filter" type="submit" class="form-action {{ $isFiltered ? 'primary' : 'secondary' }}">Filter</button>
		<button id="btn-customers-reset" type="button" class="form-action {{ $isFiltered ? 'secondary' : 'primary' }}">Reset</button>
		<a id="btn-customers-add" class="form-action secondary" href="{{ route('customers.create') }}">Add Customer</a>
	</form>

	<div style="overflow:auto;">
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
						<td>
							<div style="display:flex; gap:8px;">
								<a class="form-action btn-view" style="padding: 6px 12px; font-size:12px;" href="{{ route('customers.show', $user) }}">View</a>
								
								<a class="form-action btn-edit" style="padding: 6px 12px; font-size:12px;" href="{{ route('customers.edit', $user) }}">Edit</a>
								
								<form method="POST" action="{{ route('customers.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" style="margin:0;">
									@csrf
									@method('DELETE')
									<button type="submit" class="form-action btn-delete" style="padding: 6px 12px; font-size:12px;">Delete</button>
								</form>
							</div>
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

	<div style="margin-top:14px;">
		{{ $users->links() }}
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	var form = document.getElementById('customers-filter-form');
	var searchInput = document.getElementById('customers-search');
	var roleSelect = document.getElementById('customers-role');
	var filterBtn = document.getElementById('btn-customers-filter');
	var resetBtn = document.getElementById('btn-customers-reset');
	var addBtn = document.getElementById('btn-customers-add');

	function activate(button) {
		[filterBtn, resetBtn, addBtn].forEach(function (btn) {
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
			activate(filterBtn);
		});
	}

	if (resetBtn) {
		resetBtn.addEventListener('click', function () {
			if (searchInput) searchInput.value = '';
			if (roleSelect) roleSelect.value = '';
			activate(resetBtn);
			form.submit();
		});
	}

	if (addBtn) {
		addBtn.addEventListener('click', function () {
			activate(addBtn);
		});
	}
});
</script>

@endsection