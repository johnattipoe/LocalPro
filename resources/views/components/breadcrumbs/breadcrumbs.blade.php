@php
    $routeName = request()->route()?->getName();
    $home = ['label' => 'Home', 'url' => route('home')];
    $dashboardRoute = match (auth()->user()?->role) {
        'provider' => 'provider.dashboard',
        'admin' => 'admin.dashboard',
        default => 'customer.dashboard',
    };
    $trails = [
        'about' => [$home, ['label' => 'About LocalPro']],
        'contact' => [$home, ['label' => 'Contact support']],
        'how-it-works' => [$home, ['label' => 'How it works']],
        'privacy' => [$home, ['label' => 'Privacy']],
        'terms' => [$home, ['label' => 'Marketplace terms']],
        'services.index' => [$home, ['label' => 'Services']],
        'services.search' => [$home, ['label' => 'Services', 'url' => route('services.index')], ['label' => 'Search']],
        'services.show' => [$home, ['label' => 'Services', 'url' => route('services.index')], ['label' => $category->name ?? 'Service']],
        'professionals.index' => [$home, ['label' => 'Find a professional']],
        'providers.show' => [$home, ['label' => 'Find a professional', 'url' => route('professionals.index')], ['label' => $provider->user->name ?? 'Professional profile']],
        'providers.register' => [$home, ['label' => 'Join LocalPro']],
        'login' => [$home, ['label' => 'Log in']],
        'register' => [$home, ['label' => 'Create an account']],
        'password.request' => [$home, ['label' => 'Reset password']],
        'password.reset' => [$home, ['label' => 'Choose a new password']],
        'customer.dashboard' => [$home, ['label' => 'Customer dashboard']],
        'customer.bookings.index' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Bookings']],
        'customer.bookings.show' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Bookings', 'url' => route('customer.bookings.index')], ['label' => 'Booking details']],
        'customer.history' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Job history']],
        'customer.payments.index' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Payments']],
        'customer.profile.edit' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Profile']],
        'customer.requests.index' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Service requests']],
        'customer.requests.show' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Service requests', 'url' => route('customer.requests.index')], ['label' => 'Request details']],
        'customer.requests.create' => [$home, ['label' => 'Find a professional', 'url' => route('professionals.index')], ['label' => 'Send a service request']],
        'customer.reviews.index' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Reviews']],
        'customer.reviews.create' => [$home, ['label' => 'Service requests', 'url' => route('customer.requests.index')], ['label' => 'Write a review']],
        'customer.settings' => [$home, ['label' => 'Customer dashboard', 'url' => route('customer.dashboard')], ['label' => 'Settings']],
        'provider.dashboard' => [$home, ['label' => 'Provider dashboard']],
        'provider.availability.edit' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Availability']],
        'provider.bookings.index' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Bookings']],
        'provider.bookings.show' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Bookings', 'url' => route('provider.bookings.index')], ['label' => 'Booking details']],
        'provider.earnings.index' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Earnings']],
        'provider.profile.edit' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Professional profile']],
        'provider.reviews.index' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Reviews']],
        'provider.services.index' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'My services']],
        'provider.services.create' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'My services', 'url' => route('provider.services.index')], ['label' => 'Add a service']],
        'provider.services.edit' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'My services', 'url' => route('provider.services.index')], ['label' => 'Edit service']],
        'provider.settings.edit' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Settings']],
        'provider.verification.index' => [$home, ['label' => 'Provider dashboard', 'url' => route('provider.dashboard')], ['label' => 'Verification']],
        'admin.dashboard' => [$home, ['label' => 'Administration']],
        'admin.analytics.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Reports']],
        'admin.bookings.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Bookings']],
        'admin.categories.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Service categories']],
        'admin.payments.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Payments']],
        'admin.payouts.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Payouts']],
        'admin.providers.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => request('status') === 'pending' ? 'Provider verification' : 'Providers']],
        'admin.reports.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Reviews']],
        'admin.settings.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Settings']],
        'admin.support.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Support']],
        'admin.users.index' => [$home, ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Users']],
        'messages.index' => [$home, ['label' => 'Dashboard', 'url' => route($dashboardRoute)], ['label' => 'Messages']],
        'notifications.index' => [$home, ['label' => 'Dashboard', 'url' => route($dashboardRoute)], ['label' => 'Notifications']],
        'messages.show' => [$home, ['label' => 'Messages', 'url' => route('messages.index')], ['label' => 'Conversation']],
    ];
    $crumbs = $trails[$routeName] ?? [$home, ['label' => \Illuminate\Support\Str::headline(last(explode('.', $routeName ?? 'page')))]];
@endphp

@if(count($crumbs) > 1)
    <nav class="site-breadcrumbs" aria-label="Breadcrumb">
        <ol>
            @foreach($crumbs as $crumb)
                <li>
                    @if(!$loop->last && isset($crumb['url']))
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                    @else
                        <span @if($loop->last) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
