@extends('Layouts.user')

@section('content')
    <section class="info-panel">
        <div class="section-title" style="margin-bottom: 14px;">
            <div>
                <h2>Profile Settings</h2>
                <p>Update photo, name, phone, and password. Email is fixed.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="form-grid" style="grid-template-columns:1fr 1fr;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Name</label>
                <input class="input" id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email (fixed)</label>
                <input class="input" id="email" type="email" value="{{ $user->email }}" disabled>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone</label>
                <input class="input" id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="photo">Photo</label>
                <input class="input" id="photo" type="file" name="photo" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">New Password (optional)</label>
                <input class="input" id="password" type="password" name="password">
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input class="input" id="password_confirmation" type="password" name="password_confirmation">
            </div>

            <div class="form-actions" style="grid-column:1/-1;">
                <button type="submit" class="form-action primary">Save Changes</button>
            </div>
        </form>
    </section>
@endsection
