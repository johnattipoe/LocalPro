<header class="topbar">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark">✓</span><span>local<span>pro</span><small>VOLTA REGION</small></span></a>
    <nav class="main-nav" aria-label="Main navigation">
        <a href="{{ route('services.index') }}">Services</a>
        <a href="{{ route('professionals.index') }}">Find a professional</a>
        <a href="{{ route('how-it-works') }}">How it works</a>
        <a href="{{ route('contact') }}">Support</a>
    </nav>
    <div class="top-actions">
        @auth
            @if(auth()->user()->role === 'admin')
                <a class="login-link" href="{{ route('admin.dashboard') }}">Admin dashboard</a>
                <a class="login-link" href="{{ route('admin.support.index') }}">Support inbox</a>
            @elseif(auth()->user()->role === 'provider')
                <a class="login-link" href="{{ route('provider.dashboard') }}">Provider dashboard</a>
            @else
                <a class="login-link" href="{{ route('customer.dashboard') }}">My dashboard</a>
            @endif
            <a class="login-link" href="{{ route('messages.index') }}">Messages</a>
            <a class="login-link" href="{{ route('notifications.index') }}">Notifications</a>
        @else
            <a class="login-link" href="{{ route('login') }}">Log in</a>
            <a class="button button-small" href="{{ route('register') }}">Create an account</a>
        @endauth
    </div>
</header>
