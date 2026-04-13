<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hotel Booking Management</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    @include('partials.site-header', ['showHome' => true])
    <main class="container-wide auth-shell">
        <div class="hero-grid">
            <section class="hero-panel" style="display:flex; flex-direction:column; justify-content:center;">
                <div class="hero-kicker">Welcome back</div>
                <h1>Sign in to manage bookings with a polished hotel dashboard.</h1>
                <p>
                    Access your booking history, profile details, and member tools from one clean place built for HotelFlow guests.
                </p>
                <div class="hero-actions">
                    <a class="btn-outline" href="{{ route('register') }}">Create account</a>
                    <a class="btn-soft" href="{{ route('password.request') }}">Forgot password</a>
                </div>
            </section>

            <section class="info-panel" style="display:flex; align-items:center;">
                <div class="form-card panel" style="width:100%; max-width: 560px; margin: 0 auto;">
                    <div class="section-title" style="margin-top:0;">
                        <div>
                            <h2>{{ ($loginType ?? 'user') === 'admin' ? 'Admin Login' : 'Login' }}</h2>
                            <p>{{ ($loginType ?? 'user') === 'admin' ? 'Enter your admin credentials to continue.' : 'Enter your credentials to continue.' }}</p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="notice success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route($submitRoute ?? 'login.submit') }}" class="form-grid" style="grid-template-columns:1fr;" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" autocomplete="off" autocapitalize="off" spellcheck="false" required>
                            @error('email')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <input class="input" id="password" type="password" name="password" placeholder="Enter your password" autocomplete="new-password" required>
                            @error('password')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" style="flex-direction:row; align-items:center; gap:10px;">
                            <input id="remember" type="checkbox" name="remember" value="1" style="width:auto;">
                            <label class="form-label" for="remember" style="margin:0;">Remember me</label>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="form-action primary">Login</button>
                            <a class="form-action secondary" href="{{ route('register') }}">Register</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
