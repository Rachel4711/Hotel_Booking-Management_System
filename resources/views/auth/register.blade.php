<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Hotel Book Management</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    @include('partials.site-header', ['showHome' => true])
    <main class="container-wide auth-shell">
        <div class="hero-grid">
            <section class="hero-panel" style="display:flex; flex-direction:column; justify-content:center;">
                <div class="hero-kicker">Create your guest account</div>
                <h1>Join us now and enjoy the best rates on quality hotel rooms.</h1>
                <p>
                    Register once to unlock the guest dashboard, update your profile, and manage your hotel booking activity with ease.
                </p>
                <div class="hero-actions">
                    <a class="btn-outline" href="{{ route('login') }}">Back to login</a>
                    <a class="btn-soft" href="{{ route('password.request') }}">Reset password</a>
                </div>
            </section>

            <section class="info-panel" style="display:flex; align-items:center;">
                <div class="form-card panel" style="width:100%; max-width: 560px; margin: 0 auto;">
                    <div class="section-title" style="margin-top:0;">
                        <div>
                            <h2>Register</h2>
                            <p>Create a new account in a few steps.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register.submit') }}" class="form-grid" style="grid-template-columns:1fr;">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="name">Name</label>
                            <input class="input" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone</label>
                            <input class="input" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <div style="background: rgba(111, 89, 72, 0.08); border: 1px solid rgba(111, 89, 72, 0.2); border-radius: 12px; padding: 12px; margin-bottom: 8px; font-size: 14px; color: #4a3c31;">
                                <strong>Requirements:</strong>
                                <ul style="margin: 8px 0 0; padding-left: 20px;">
                                    <li>At least 8 characters</li>
                                    <li>At least one uppercase letter (A-Z)</li>
                                    <li>At least one lowercase letter (a-z)</li>
                                    <li>At least one special character (!@#$%^&* etc)</li>
                                </ul>
                            </div>
                            <input class="input" id="password" type="password" name="password" required>
                            @error('password')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input class="input" id="password_confirmation" type="password" name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="input-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="form-action primary">Create account</button>
                            <a class="form-action secondary" href="{{ route('login') }}">Login</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
