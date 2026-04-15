@extends('Layouts.admin')
@section('content')

<h1>Add Customer</h1>

{{-- @if($errors->any())
	<div class="notice error">
		<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif --}}

<form method="POST" action="{{ route('customers.store') }}" style="display:grid; grid-template-columns:1fr; gap:12px; margin-top:12px; width:100%; max-width:760px;">
		@csrf
		<div>
			<label>Name</label>
			<input class="input" type="text" name="name" value="{{ old('name') }}" required>
		</div>
		<div>
			<label>Email</label>
			<input class="input" type="email" name="email" value="{{ old('email') }}" required>
		</div>
		<div>
			<label>Phone</label>
			<input class="input" type="text" name="phone" value="{{ old('phone') }}">
		</div>
		<div>
			<label>Role</label>
			<select class="input" name="role" required>
				<option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
				<option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
			</select>
		</div>
		<div>
			<label>Password</label>
			<input class="input" type="password" name="password" required>
		</div>
		<div>
			<label>Confirm Password</label>
			<input class="input" type="password" name="password_confirmation" required>
		</div>

		<div style="display:flex; gap:10px; grid-column:1/-1;">
			<button type="submit" class="form-action primary">Save</button>
			<a class="form-action secondary" href="{{ route('customers.index') }}">Cancel</a>
		</div>
	</form>

@endsection