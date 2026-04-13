<header class="marketing-header">
    @php
        $isRegisterPage = request()->routeIs('register');
        $isLoginPage = request()->routeIs('login');
        $loginActive = $isLoginPage || ! $isRegisterPage;
        $registerActive = $isRegisterPage;
    @endphp

    <div class="marketing-header-inner">
        <div class="marketing-brand-wrap">
            <a href="{{ url('/') }}" class="marketing-brand">Hotel Management System</a>
        </div>

        <div class="marketing-nav-right">
            @if(($showHome ?? false) === true)
                <a class="marketing-home" href="{{ url('/') }}">Homepage</a>
            @endif
            <a class="{{ $loginActive ? 'marketing-login' : 'marketing-cta' }}" href="{{ route('login') }}">Login</a>
            <a class="{{ $registerActive ? 'marketing-login' : 'marketing-cta' }}" href="{{ route('register') }}">Register</a>
        </div>
    </div>
</header>
