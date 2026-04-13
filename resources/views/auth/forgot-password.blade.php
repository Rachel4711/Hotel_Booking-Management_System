<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | HotelFlow</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    @include('partials.site-header', ['showHome' => true])
    <main class="container-wide" style="padding: 42px 0; max-width: 720px;">
        <section class="hero-panel">
            <div class="hero-kicker">Password recovery</div>
            <h1>Request a reset link with a simple, clean recovery flow.</h1>
            <p>
                We will send the reset link to your email so you can get back into your account quickly.
            </p>
        </section>

        <section class="info-panel" style="margin-top: 20px;">
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

            <form method="POST" action="{{ route('password.email') }}" class="form-grid" style="grid-template-columns:1fr;">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="form-action primary">Send reset link</button>
                    <a class="form-action secondary" href="{{ route('login') }}">Back to login</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
