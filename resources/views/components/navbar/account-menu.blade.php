@php($accountUser = auth()->user())
<details class="public-account-menu">
    <summary class="public-account-trigger" aria-label="Account menu for {{ $accountUser->name }}">
        <span class="public-account-avatar">{{ collect(explode(' ', $accountUser->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(1)->join('') }}</span>
        <span class="public-account-chevron" aria-hidden="true">&#8964;</span>
    </summary>
    <div class="public-account-dropdown">
        <div class="public-account-heading">
            <span class="public-account-avatar" aria-hidden="true">{{ collect(explode(' ', $accountUser->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(1)->join('') }}</span>
            <span><strong>{{ $accountUser->name }}</strong><small>{{ __('ui.'.($accountUser->role === 'provider' ? 'service_provider' : ($accountUser->role === 'admin' ? 'administrator' : 'customer'))) }} {{ __('ui.account') }}</small></span>
        </div>
        @if($accountUser->role === 'customer')
            <a href="{{ route('customer.dashboard') }}">{{ __('ui.dashboard') }}</a>
            <a href="{{ route('customer.settings') }}">{{ __('ui.settings') }}</a>
            <a href="{{ route('customer.profile.edit') }}">{{ __('ui.my_profile') }}</a>
        @elseif($accountUser->role === 'provider')
            <a href="{{ route('provider.dashboard') }}">{{ __('ui.dashboard') }}</a>
            <a href="{{ route('provider.settings.edit') }}">{{ __('ui.settings') }}</a>
            <a href="{{ route('provider.profile.edit') }}">{{ __('ui.my_profile') }}</a>
        @else
            <a href="{{ route('admin.dashboard') }}">{{ __('ui.dashboard') }}</a>
            <a href="{{ route('admin.settings.index') }}">{{ __('ui.settings') }}</a>
            <a href="{{ route('admin.support.index') }}">Support inbox</a>
        @endif
        <a href="{{ route('messages.index') }}">{{ __('ui.messages') }}</a>
        <a href="{{ route('notifications.index') }}">{{ __('ui.notifications') }}</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">{{ __('ui.log_out') }}</button></form>
    </div>
</details>
