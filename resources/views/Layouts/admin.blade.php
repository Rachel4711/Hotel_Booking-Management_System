<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    {{-- @include('partials.site-header', ['showHome' => true]) --}}

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

    <div class="admin-container">
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
                    <li class="menu-item {{ request()->routeIs('bookings.*') ? 'active-menu active' : '' }}">
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="uil uil-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="admin-content">
            
            @if (session('success'))
                <div class="notice success" style="margin-bottom: 15px; padding: 10px; background-color: #d4edda; color: #155724; border-radius: 4px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="notice error" style="margin-bottom: 15px; padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 4px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
            
        </main>
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>