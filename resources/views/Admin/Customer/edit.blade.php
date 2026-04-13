@extends('Layouts.admin')
@section('content')

<h1>Edit Customer</h1>

@if($errors->any())
	<div class="notice error">
		<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<div class="panel" style="margin-top:12px; max-width:760px; background:#fff; border:1px solid #dbe3ea; border-radius:10px; padding:16px;">
	<form method="POST" action="{{ route('customers.update', $customer) }}" style="display:grid; gap:12px;">
		@csrf
		@method('PUT')

		<div>
			<label>Name</label>
			<input class="input" type="text" name="name" value="{{ old('name', $customer->name) }}" required>
		</div>
		<div>
			<label>Email</label>
			<input class="input" type="email" name="email" value="{{ old('email', $customer->email) }}" required>
		</div>
		<div>
			<label>Phone</label>
			<input class="input" type="text" name="phone" value="{{ old('phone', $customer->phone) }}">
		</div>
		<div>
			<label>Role</label>
			<select class="input" name="role" required>
				<option value="user" {{ old('role', $customer->role) === 'user' ? 'selected' : '' }}>User</option>
				<option value="admin" {{ old('role', $customer->role) === 'admin' ? 'selected' : '' }}>Admin</option>
			</select>
		</div>
		<div>
			<label>New Password (optional)</label>
			<input class="input" type="password" name="password">
		</div>
		<div>
			<label>Confirm New Password</label>
			<input class="input" type="password" name="password_confirmation">
		</div>

		<div style="display:flex; gap:10px;">
			<button type="submit" class="form-action primary">Update</button>
			<a class="form-action secondary" href="{{ route('customers.index') }}">Cancel</a>
		</div>
	</form>
</div>

@endsection