@extends('Layouts.admin')
@section('content')

<h1>Admin Profile</h1>

@if(session('success'))
	<div class="notice success">{{ session('success') }}</div>
@endif

<div class="panel" style="margin-top:12px; max-width:720px; background:#fff; border:1px solid #dbe3ea; border-radius:10px; padding:18px;">
	<form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
		@csrf
		@method('PUT')

		<div style="display:grid; gap:12px;">
			<div>
				<label>Name</label>
				<input class="input" type="text" name="name" value="{{ old('name', $admin->name) }}" required>
			</div>
			<div>
				<label>Email (fixed)</label>
				<input class="input" type="email" value="{{ $admin->email }}" disabled>
			</div>
			<div>
				<label>Phone</label>
				<input class="input" type="text" name="phone" value="{{ old('phone', $admin->phone) }}">
			</div>
			<div>
				<label>Photo</label>
				<input class="input" type="file" name="photo" accept="image/*">
			</div>
		</div>

		<div style="margin-top:14px;">
			<button type="submit" class="form-action primary">Save Profile</button>
		</div>
	</form>
</div>


@endsection