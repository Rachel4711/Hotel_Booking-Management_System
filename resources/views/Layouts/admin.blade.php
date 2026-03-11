<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite([
        'resources/css/Admin/admin.css',
        'resources/js/Admin/admin.js'
    ])
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="header-left">
            <i class="uil uil-bars menu-toggle"></i>
            <div class="logo">Hotel Admin</div>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.profile') }}">
                <img src="{{ asset('images/admin-avatar.png') }}" class="avatar-small" alt="Admin">
            </a>
        </div>
    </header>

    <!-- Sidebar + Content -->
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-top">
                <img src="{{ asset('images/admin-avatar.png') }}" class="avatar-large" alt="Admin">
                <h3>Admin Test</h3>
            </div>
            <nav class="sidebar-menu">
                <ul>
                    <li class="{{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="uil uil-apps"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <div class="menu-title">
                            <span>
                                <i class="uil uil-users-alt"></i>
                                Customers
                            </span>
                            <i class="uil uil-angle-left-b arrow"></i>
                        </div>

                        <ul class="submenu">
                            <li class="{{ request()->routeIs('customers.index') ? 'active-link' : '' }}">
                                <a href="{{ route('customers.index') }}">View Customer List</a>
                            </li>

                            <li class="{{ request()->routeIs('customers.create') ? 'active-link' : '' }}">
                                <a href="{{ route('customers.create') }}">Add Customer</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item {{ request()->routeIs('hotels.*') ? 'active' : '' }}">
                        <div class="menu-title">
                            <span>
                                <i class="uil uil-bed-double"></i>
                                Hotel
                            </span>
                            <i class="uil uil-angle-left-b arrow"></i>
                        </div>

                        <ul class="submenu">
                            <li class="{{ request()->routeIs('hotels.index') ? 'active-link' : '' }}">
                                <a href="{{ route('hotels.index') }}">View Hotel List</a>
                            </li>
                            <li class="{{ request()->routeIs('hotels.create') ? 'active-link' : '' }}">
                                <a href="{{ route('hotels.create') }}">Add Hotel</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item {{ request()->routeIs('bookings.index') || request()->routeIs('bookings.create') ? 'active-menu active' : '' }}">
                        <div class="menu-title">
                            <span>
                                <i class="uil uil-invoice"></i>
                                Booking
                            </span>
                            <i class="uil uil-angle-left-b arrow"></i>
                        </div>

                        <ul class="submenu">
                            <li class="{{ request()->routeIs('bookings.index') ? 'active-link' : '' }}">
                                <a href="{{ route('bookings.index') }}">View Booking List</a>
                            </li>
                            <li class="{{ request()->routeIs('bookings.create') ? 'active-link' : '' }}">
                                <a href="{{ route('bookings.create') }}">Add Booking</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-bottom">
                <form method="POST" action="#">
                    @csrf
                    <button class="logout-btn">
                        {{-- <i class="uil uil-sign-out-alt"></i> --}}
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            @yield('content')
        </main>
    </div>
</body>
</html>