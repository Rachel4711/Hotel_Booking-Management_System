<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <script defer src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script defer src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script defer src="{{ asset('js/admin-react.js') }}"></script>
</head>
<body>
    @include('partials.site-header', ['showHome' => true])
    <div id="admin-root"
         data-admin-name="{{ auth()->user()?->name ?? 'Admin' }}"
         data-admin-photo="{{ auth()->user()?->photo ? asset('storage/' . auth()->user()->photo) : asset('images/admin-avatar.png') }}"
         data-dashboard-url="{{ route('dashboard') }}"
         data-customers-index-url="{{ route('customers.index') }}"
         data-customers-create-url="{{ route('customers.create') }}"
         data-hotels-index-url="{{ route('hotels.index') }}"
         data-hotels-create-url="{{ route('hotels.create') }}"
         data-bookings-index-url="{{ route('bookings.index') }}"
         data-bookings-create-url="{{ route('bookings.create') }}"
         data-profile-url="{{ route('admin.profile') }}"
         data-logout-url="{{ route('logout') }}">
        <div id="blade-content" style="display:none;">@yield('content')</div>
    </div>
</body>
</html>