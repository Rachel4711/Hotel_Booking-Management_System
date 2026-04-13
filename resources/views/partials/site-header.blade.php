<header class="marketing-header">
    @php
        $isRegisterPage = request()->routeIs('register');
        $isLoginPage = request()->routeIs('login') || request()->routeIs('adminLogin');
        $loginActive = $isLoginPage || ! $isRegisterPage;
        $registerActive = $isRegisterPage;
    @endphp

    <div class="marketing-header-inner">
        <div class="marketing-brand-wrap">
            <a href="{{ url('/') }}" class="marketing-brand">Hotel Booking Management</a>
        </div>

        <div class="marketing-nav-right">
            @if(($showHome ?? false) === true)
                <a class="marketing-home" href="{{ url('/') }}">Homepage</a>
            @endif
            <div class="login-menu">
                <button type="button" class="{{ $loginActive ? 'marketing-login' : 'marketing-cta' }}">Login</button>
                <div class="login-menu-dropdown">
                    <a href="{{ route('login') }}">User Login</a>
                    <a href="{{ route('adminLogin') }}">Admin Login</a>
                </div>
            </div>
            <a class="{{ $registerActive ? 'marketing-login' : 'marketing-cta' }}" href="{{ route('register') }}">Register</a>
        </div>
    </div>
</header>
