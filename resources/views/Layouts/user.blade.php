<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    @include('partials.site-header', ['showHome' => true])
    <main class="container-wide auth-shell">
        <div class="info-panel" style="margin-bottom:18px; display:flex; justify-content:space-between; align-items:center; gap:12px;">
            <div>
                <h2 style="margin:0;">User Panel</h2>
                <p style="margin:6px 0 0; color:var(--muted);">Manage your profile information and account security.</p>
            </div>
            <div style="display:flex; gap:10px; align-items:center;">
                @if (!request()->routeIs('user.profile.edit'))
                    <a class="form-action secondary" href="{{ route('user.profile.edit') }}">My Profile</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="form-action primary">Logout</button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="notice success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>