@extends('Layouts.admin')
@section('content')

<h1>Customer List</h1>

@if(session('success'))
	<div class="notice success">{{ session('success') }}</div>
@endif

<div class="panel" style="margin-top:12px; background:#fff; border:1px solid #dbe3ea; border-radius:10px; padding:16px;">
	<form method="GET" action="{{ route('customers.index') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
		<input class="input" style="max-width:280px;" type="text" name="search" value="{{ $search }}" placeholder="Search name, phone, email">
		<select class="input" style="max-width:180px;" name="role">
			<option value="">All roles</option>
			<option value="user" {{ $role === 'user' ? 'selected' : '' }}>User</option>
			<option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
		</select>
		<button type="submit" class="form-action primary">Filter</button>
		<a class="form-action secondary" href="{{ route('customers.index') }}">Reset</a>
		<a class="form-action secondary" href="{{ route('customers.create') }}">Add Customer</a>
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
						<td style="padding:10px; border-bottom:1px solid #f1f4f7; display:flex; gap:8px;">
							<a class="form-action secondary" href="{{ route('customers.show', $user) }}">View</a>
							<a class="form-action secondary" href="{{ route('customers.edit', $user) }}">Edit</a>
							<form method="POST" action="{{ route('customers.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
								@csrf
								@method('DELETE')
								<button type="submit" class="form-action secondary">Delete</button>
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

	<div style="margin-top:14px;">
		{{ $users->links() }}
	</div>
</div>

@endsection