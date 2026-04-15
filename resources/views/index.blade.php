<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System | Homepage</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    @include('partials.site-header', ['showHome' => false])

    <main class="container-wide auth-shell">
        <div class="hero-grid">
            <section class="hero-panel" style="display:flex; flex-direction:column; justify-content:center;">
                <div class="hero-kicker">Hotel Booking Management System</div>
                <h1>Welcome to HotelFlow.</h1>
                <p>
                    Manage users, bookings, and profiles in one system with role-based access for admin and normal users.
                </p>
            </section>

            <section class="info-panel" style="display:flex; align-items:center;">
                <div class="form-card panel" style="width:100%; max-width: 560px; margin: 0 auto;">
                    <div class="section-title" style="margin-top:0;">
                        <div>
                            <h2>Get Started</h2>
                        </div>
                    </div>
                   <p style="margin: 0; color: var(--muted);">
                        Explore our hotel booking platform designed for seamless reservations, guest management, and comprehensive booking administration.
                    </p>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
 