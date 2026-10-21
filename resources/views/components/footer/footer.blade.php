<footer class="site-footer">
    <div class="site-footer-main">
        <div class="site-footer-intro">
            <a class="site-footer-brand" href="{{ route('home') }}" aria-label="LocalPro home">
                <img class="site-footer-logo-image" src="{{ asset('logo/localpro-mark.png') }}" alt="">
                <span class="site-footer-brand-copy"><strong>Local<span>Pro</span></strong><small>Trusted local services<br>in Ho and the Volta Region</small></span>
            </a>
            <p><i data-lucide="handshake" aria-hidden="true"></i> Find trusted local professionals, talk through the job, and agree on the details before work begins.</p>
            <span class="footer-location"><i data-lucide="map-pin" aria-hidden="true"></i> Made for Ho and the Volta Region</span>
            <div class="footer-service-areas" aria-label="Local service areas">
                <span><i data-lucide="map-pin" aria-hidden="true"></i> Ho</span>
                <span><i data-lucide="map-pin" aria-hidden="true"></i> Hohoe</span>
                <span><i data-lucide="map-pin" aria-hidden="true"></i> Keta</span>
                <span><i data-lucide="map-pin" aria-hidden="true"></i> Aflao</span>
            </div>
        </div>

        <nav class="site-footer-column" aria-label="Explore LocalPro">
            <h2><i data-lucide="compass" aria-hidden="true"></i> {{ __('ui.explore') }}</h2>
            <a href="{{ route('services.index') }}"><i data-lucide="briefcase-business" aria-hidden="true"></i> {{ __('ui.browse_services') }}</a>
            <a href="{{ route('professionals.index') }}"><i data-lucide="search" aria-hidden="true"></i> {{ __('ui.find_professional') }}</a>
            <a href="{{ route('how-it-works') }}"><i data-lucide="clipboard-check" aria-hidden="true"></i> {{ __('ui.how_it_works') }}</a>
            <a href="{{ route('about') }}"><i data-lucide="circle-help" aria-hidden="true"></i> {{ __('ui.about_localpro') }}</a>
        </nav>

        <nav class="site-footer-column" aria-label="For LocalPro members">
            <h2><i data-lucide="users-round" aria-hidden="true"></i> {{ __('ui.join_community') }}</h2>
            <a href="{{ route('register') }}"><i data-lucide="user-round" aria-hidden="true"></i> Create a customer account</a>
            <a href="{{ route('providers.register') }}"><i data-lucide="briefcase-business" aria-hidden="true"></i> Become a professional</a>
            <a href="{{ route('login') }}"><i data-lucide="log-in" aria-hidden="true"></i> {{ __('ui.log_in') }} to LocalPro</a>
        </nav>

        <nav class="site-footer-column" aria-label="Help and policies">
            <h2><i data-lucide="life-buoy" aria-hidden="true"></i> {{ __('ui.help_information') }}</h2>
            <a href="{{ route('home') }}#answers"><i data-lucide="circle-help" aria-hidden="true"></i> Frequently asked questions</a>
            <a href="{{ route('contact') }}"><i data-lucide="mail" aria-hidden="true"></i> {{ __('ui.contact_support') }}</a>
            <a href="{{ route('privacy') }}"><i data-lucide="shield-check" aria-hidden="true"></i> {{ __('ui.privacy') }}</a>
            <a href="{{ route('terms') }}"><i data-lucide="file-text" aria-hidden="true"></i> {{ __('ui.terms') }}</a>
        </nav>
    </div>

    <div class="site-footer-promise">
        <span class="site-footer-promise-icon"><i data-lucide="shield-check" aria-hidden="true"></i></span>
        <div>
            <strong>Good work starts with clear expectations.</strong>
            <p>Review profiles, discuss the job, and agree on the price and schedule directly before work begins.</p>
        </div>
        <a href="{{ route('how-it-works') }}">See how it works <i data-lucide="arrow-right" aria-hidden="true"></i></a>
    </div>

    <div class="site-footer-bottom">
        <small><i data-lucide="map-pin" aria-hidden="true"></i> &#169; {{ date('Y') }} LocalPro. Connecting Ho and the Volta Region.</small>
        <span class="site-footer-bottom-links">
            <a href="{{ route('privacy') }}">Privacy</a>
            <a href="{{ route('terms') }}">Terms</a>
            <a href="{{ route('contact') }}">Support</a>
            @unless(request()->routeIs('customer.*', 'provider.*', 'admin.*'))
            <button class="cookie-footer-button" type="button" data-open-cookie-settings>{{ __('ui.cookie_settings') }}</button>
            @endunless
            <a href="{{ route('home') }}#top">Back to top <i data-lucide="arrow-up" aria-hidden="true"></i></a>
        </span>
    </div>
</footer>
@unless(request()->routeIs('customer.*', 'provider.*', 'admin.*'))
    @include('components.cookie-consent.cookie-consent')
@endunless
