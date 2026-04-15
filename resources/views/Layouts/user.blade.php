<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hotel Booking Management')</title>
    <link rel="stylesheet" href="{{ asset('css/user-theme.css') }}">
</head>
<body>
    <header class="marketing-header">
        <div class="marketing-header-inner">
            <div class="marketing-brand-wrap">
                <a href="{{ route('home') }}" class="marketing-brand">LuxeHotel</a>
                <span class="marketing-brand-sub">Book your perfect stay</span>
            </div>
            
            <nav class="marketing-nav-right">
                <a href="{{ route('home') }}">Home</a>
                
                @auth
                    <a href="{{ route('user.history') }}">My Bookings</a>
                    <div class="login-menu">
                        <a href="javascript:void(0)" style="display:flex; align-items:center; gap:5px;">
                            {{ Auth::user()->name ?? 'My Account' }} <span>▼</span>
                        </a>
                        <div class="login-menu-dropdown">
                            <a href="{{ route('user.profile.edit') }}">My Profile</a>
                            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="form-action primary" style="min-height: 38px; padding: 6px 16px;">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container-wide auth-shell">
        @if (session('success'))
            <div class="notice success" style="padding:15px; background:rgba(126,101,82,0.1); color:#7e6552; border-radius:12px; margin-bottom:20px; border:1px solid #d8c9bd;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="notice error" style="padding:15px; background:rgba(207,74,60,0.1); color:#8a2d24; border-radius:12px; margin-bottom:20px; border:1px solid rgba(207,74,60,0.2);">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="user-footer">
        <div class="container-wide">
            <p style="margin: 0; font-weight: 600;">&copy; {{ date('Y') }} LuxeHotel Management. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>