@extends('Layouts.user')

@section('title', 'My Profile')

@section('content')
    <div class="info-panel" style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h2 style="margin:0; color:var(--brand-dark);">User Profile</h2>
            <p style="margin:6px 0 0; color:var(--muted);">Manage your personal information and account security.</p>
        </div>
    </div>

    <div class="panel">
        <p>Your profile forms go here...</p>
    </div>
@endsection