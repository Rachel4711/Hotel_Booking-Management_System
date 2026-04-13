<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Hotel Booking Management</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    @include('partials.site-header', ['showHome' => true])
    <main class="container-wide" style="padding: 42px 0; max-width: 720px;">
        <section class="hero-panel">
            <div class="hero-kicker">Reset password</div>
            <h1>Create a new password and continue securely.</h1>
            <p>Choose a strong password and confirm it below.</p>
        </section>

        <section class="info-panel" style="margin-top: 20px;">
            @if ($errors->any())
                <div class="notice error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="form-grid" style="grid-template-columns:1fr;">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="input" id="email" type="email" name="email" value="{{ old('email', $email) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <input class="input" id="password" type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input class="input" id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="form-action primary">Reset Password</button>
                    <a class="form-action secondary" href="{{ route('login') }}">Back to login</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
