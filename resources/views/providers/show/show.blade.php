<!doctype html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $provider->user->name }} — {{ $provider->profession }} | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
</head>
<body class="directory-page provider-profile-page">
@include('components.navbar.navbar')
<main class="provider-profile-content">
    <header class="provider-profile-hero">
        <div class="provider-profile-identity">
            <div class="provider-profile-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($provider->user->name, 0, 1)) }}</div>
            <div class="provider-profile-title">
                <span class="provider-profile-verified">
                    <i data-lucide="shield-check" aria-hidden="true"></i> IDENTITY VERIFIED
                </span>
                <h1>{{ $provider->user->name }}</h1>
                <p class="provider-profile-profession">{{ $provider->profession }}</p>
                <p class="provider-profile-location">
                    <i data-lucide="map-pin" aria-hidden="true"></i>{{ $provider->city ?: ($provider->user->city ?: 'Ho') }}, {{ $provider->region ?: 'Volta Region' }}
                </p>
            </div>
        </div>
        <div class="provider-profile-hero-action">
            <span class="provider-profile-action-label">LOOKING FOR HELP?</span>
            @auth
                @if(auth()->user()->role === 'customer')
                    <a class="button" href="{{ route('customer.requests.create', $provider) }}">Request this service <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                @else
                    <p class="profile-help">Sign in with a customer account to request a service.</p>
                @endif
            @else
                <a class="button" href="{{ route('customer.requests.create', $provider) }}">Log in to request <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            @endauth
        </div>
        <div class="provider-profile-metrics" aria-label="Professional summary">
            <div><span class="provider-metric-icon"><i data-lucide="star" aria-hidden="true"></i></span><span><strong>{{ number_format((float) $provider->average_rating, 1) }}</strong><small>{{ $provider->review_count }} {{ \Illuminate\Support\Str::plural('review', $provider->review_count) }}</small></span></div>
            <div><span class="provider-metric-icon"><i data-lucide="briefcase-business" aria-hidden="true"></i></span><span><strong>{{ $provider->services->count() }}</strong><small>{{ \Illuminate\Support\Str::plural('service', $provider->services->count()) }} listed</small></span></div>
            <div><span class="provider-metric-icon"><i data-lucide="credit-card" aria-hidden="true"></i></span><span><strong>@if($provider->starting_price)GH₵ {{ number_format((float) $provider->starting_price, 2) }}@else Quote-based @endif</strong><small>Starting price</small></span></div>
        </div>
    </header>

    <div class="provider-profile-layout">
        <div class="provider-profile-main-column">
            <section class="provider-profile-section provider-about-section" aria-labelledby="provider-about-title">
                <div class="provider-section-heading"><span class="provider-section-icon"><i data-lucide="user-round" aria-hidden="true"></i></span><div><span class="provider-profile-kicker">GET TO KNOW YOUR PROFESSIONAL</span><h2 id="provider-about-title">About {{ $provider->user->name }}</h2></div></div>
                <p class="provider-profile-bio">{{ $provider->bio ?: 'This professional has not added an introduction yet. Send a service request to discuss your needs and ask any questions.' }}</p>
                @if($provider->service_area)
                    <div class="provider-service-area"><i data-lucide="map-pin" aria-hidden="true"></i><span><small>SERVICE AREA</small><strong>{{ $provider->service_area }}</strong></span></div>
                @endif
                @if($provider->service_mode === 'customer_location')
                    <div class="provider-map-directions provider-map-service-note"><i data-lucide="truck" aria-hidden="true"></i><span><strong>This professional travels to customers</strong><small>{{ $provider->service_area ?: 'Contact them to confirm whether your location is covered.' }}</small></span></div>
                @else
                    <a class="provider-map-directions" href="{{ $provider->directions_url }}" target="_blank" rel="noopener noreferrer"><i data-lucide="map-pinned" aria-hidden="true"></i><span><strong>{{ $provider->location_latitude !== null && $provider->location_longitude !== null || $provider->location_address ? 'Get directions to business' : 'Approximate town location' }}</strong><small>{{ $provider->location_address ?: ($provider->location_latitude !== null && $provider->location_longitude !== null ? $provider->location_latitude.', '.$provider->location_longitude : $provider->city.', '.($provider->region ?: 'Volta Region')) }}</small></span><i data-lucide="arrow-up-right" aria-hidden="true"></i></a>
                @endif
            </section>

            <section class="provider-profile-section provider-services-section" aria-labelledby="provider-services-title">
                <div class="provider-section-heading provider-services-heading"><span class="provider-section-icon"><i data-lucide="wrench" aria-hidden="true"></i></span><div><span class="provider-profile-kicker">HOW I CAN HELP</span><h2 id="provider-services-title">Services offered</h2></div><span class="provider-section-count">{{ $provider->services->count() }}</span></div>
                @if($provider->services->isNotEmpty())
                    <div class="provider-profile-service-grid">
                        @foreach($provider->services as $service)
                            @php($serviceIcon = in_array($service->category?->icon, ['water', 'bolt', 'hammer', 'wrench', 'brush', 'phone', 'snowflake', 'sparkles', 'scissors', 'needle', 'camera', 'tool'], true) ? $service->category?->icon : 'tool')
                            <article class="provider-profile-service">
                                <span class="provider-service-icon"><i data-lucide="{{ $serviceIcon }}" aria-hidden="true"></i></span>
                                <div class="provider-service-copy"><h3>{{ $service->title }}</h3><p>{{ $service->description ?: 'Contact this professional to discuss what is included.' }}</p></div>
                                <span class="provider-service-price">{{ $service->pricing_type === 'quote' ? 'Request a quote' : 'GH₵ ' . number_format((float) $service->price, 2) . ($service->pricing_type === 'hourly' ? ' / hour' : '') }}</span>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="provider-profile-empty"><i data-lucide="briefcase-business" aria-hidden="true"></i><div><strong>Service details coming soon</strong><p>Send a request to ask about this professional’s work.</p></div></div>
                @endif
            </section>

            <section class="provider-profile-section provider-reviews-section" aria-labelledby="provider-reviews-title">
                <div class="provider-section-heading"><span class="provider-section-icon"><i data-lucide="star" aria-hidden="true"></i></span><div><span class="provider-profile-kicker">COMMUNITY FEEDBACK</span><h2 id="provider-reviews-title">Customer reviews</h2></div><span class="provider-section-count">{{ $provider->reviews->count() }}</span></div>
                @forelse($provider->reviews as $review)
                    <article class="provider-profile-review">
                        <div class="provider-review-topline"><strong>{{ $review->customer->name }}</strong><time datetime="{{ $review->created_at->toDateString() }}">{{ $review->created_at->format('M Y') }}</time></div>
                        <div class="provider-review-rating" aria-label="{{ $review->rating }} out of 5 stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}<span>{{ $review->rating }}.0</span></div>
                        <p>{{ $review->comment ?: 'Customer left a rating without a written comment.' }}</p>
                    </article>
                @empty
                    <div class="provider-profile-empty"><i data-lucide="message-circle" aria-hidden="true"></i><div><strong>No customer reviews yet</strong><p>Reviews from completed work will appear here.</p></div></div>
                @endforelse
            </section>
        </div>

        <aside class="provider-profile-sidebar">
            <section class="provider-profile-side-card provider-availability-card" aria-labelledby="provider-availability-title">
                <div class="provider-side-card-heading"><span class="provider-section-icon"><i data-lucide="clock-3" aria-hidden="true"></i></span><div><span class="provider-profile-kicker">PLAN AHEAD</span><h2 id="provider-availability-title">Availability</h2></div></div>
                @if($provider->availability && !empty($provider->availability['days']))
                    <p class="provider-availability-intro">Usual working days</p>
                    <div class="provider-availability-days">@foreach($provider->availability['days'] as $day)<span>{{ $day }}</span>@endforeach</div>
                    @if($provider->availability['start'] ?? null)
                        <div class="provider-availability-hours"><i data-lucide="clock-3" aria-hidden="true"></i><span>{{ $provider->availability['start'] }}@if($provider->availability['end'] ?? null) – {{ $provider->availability['end'] }}@endif</span></div>
                    @endif
                @else
                    <p class="provider-profile-side-copy">Availability has not been listed. Send a request to ask about a suitable time.</p>
                @endif
            </section>

            <section class="provider-profile-side-card provider-pricing-card" aria-labelledby="provider-pricing-title">
                <div class="provider-side-card-heading"><span class="provider-section-icon"><i data-lucide="credit-card" aria-hidden="true"></i></span><div><span class="provider-profile-kicker">CLEAR EXPECTATIONS</span><h2 id="provider-pricing-title">Pricing</h2></div></div>
                <strong class="provider-pricing-value">@if($provider->starting_price)From GH₵ {{ number_format((float) $provider->starting_price, 2) }}@else Request a quote @endif</strong>
                <p class="provider-profile-side-copy">Confirm the job details and final price with the professional before work begins.</p>
            </section>

            <section class="provider-profile-contact-card">
                <span class="provider-contact-icon"><i data-lucide="message-circle" aria-hidden="true"></i></span>
                <h2>Have a job in mind?</h2>
                <p>Share what you need and discuss the details directly.</p>
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a class="button" href="{{ route('customer.requests.create', $provider) }}">Send a request <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                    @else
                        <p class="profile-help">Service requests are available to customer accounts.</p>
                    @endif
                @else
                    <a class="button" href="{{ route('customer.requests.create', $provider) }}">Get started <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                @endauth
            </section>
        </aside>
    </div>
</main>
@include('components.footer.footer')
</body>
</html>
