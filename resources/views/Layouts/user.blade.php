<!-- This is example format, layout of user website-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- TODO: Replace with your actual title -->
    <title>Your title</title>

    <!-- TODO: Replace with your actual CSS and JS files -->
    {{-- @vite([
        'resources/css/Admin/admin.css',
        'resources/js/Admin/admin.js'
    ]) --}}

</head>
<body>
    <!-- TODO: Replace with your actual header content -->
    {{-- <header class="admin-header">
        <div class="header-left">
            <i class="uil uil-bars menu-toggle"></i>
            <div class="logo">Hotel Admin</div>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.profile') }}">
                <img src="{{ asset('images/admin-avatar.png') }}" class="avatar-small" alt="Admin">
            </a>
        </div>
    </header> --}}

    <!-- Main Content -->
    <main class="admin-content">
        @yield('content')
    </main>

    <!--TODO: Replace with your actual footer content -->
    {{-- <footer></footer> --}}
</body>
</html>