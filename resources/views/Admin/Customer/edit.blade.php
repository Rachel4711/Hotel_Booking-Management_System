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

<form method="POST" action="{{ route('customers.update', $customer) }}" style="display:grid; grid-template-columns:1fr; gap:12px; margin-top:12px; width:100%; max-width:760px;">
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

		<div style="display:flex; gap:10px; grid-column:1/-1;">
			<button type="submit" class="form-action primary">Update</button>
			<a class="form-action secondary" href="{{ route('customers.index') }}">Cancel</a>
		</div>
	</form>

@endsection