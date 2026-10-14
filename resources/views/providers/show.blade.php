<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $provider->user->name }} | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body>
<header class="topbar">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark">&#10003;</span><span>local<span>pro</span><small>VOLTA REGION</small></span></a>
    <nav class="main-nav"><a href="{{ route('home') }}#services">Services</a><a href="{{ route('home') }}#how">How it works</a></nav>
    <div class="top-actions">
        @auth
            @if(auth()->user()->role === 'customer')<a class="login-link" href="{{ route('customer.requests.index') }}">My requests</a>@else<a class="login-link" href="{{ route('home') }}">Home</a>@endif
        @else
            <a class="login-link" href="{{ route('login') }}">Log in</a>
            <a class="button button-small" href="{{ route('register') }}">Sign up</a>
        @endauth
    </div>
</header>
<main class="category-show">
    <a class="text-link" href="{{ route('home') }}">&larr; Back to LocalPro</a>
    <article class="profile-card">
        <div class="profile-avatar">{{ mb_substr($provider->user->name, 0, 1) }}</div>
        <span class="verified-filter">&#10003; IDENTITY VERIFIED</span>
        <h1>{{ $provider->user->name }}</h1>
        <h2>{{ $provider->profession }}</h2>
        <p>Ho, {{ $provider->region }}</p>
        <p>{{ $provider->bio ?: 'Local professional serving customers in Ho and nearby communities.' }}</p>@if($provider->availability)<div class="provider-availability"><small>AVAILABILITY</small><span>{{ implode(', ',$provider->availability['days'] ?? []) }}</span>@if($provider->availability['start'] ?? null)<span>{{ $provider->availability['start'] }}–{{ $provider->availability['end'] ?? '' }}</span>@endif</div>@endif
        <div class="profile-details">
            <span>&#9733; {{ number_format($provider->average_rating, 1) }} <small>({{ $provider->review_count }} reviews)</small></span>
            <span>Starting at <b>@if($provider->starting_price)GH&#8373; {{ number_format($provider->starting_price, 2) }}@else Request a quote @endif</b></span>
        </div>
        @auth
            @if(auth()->user()->role === 'customer')
                <a class="button" href="{{ route('customer.requests.create', $provider) }}">Request this service <span>&rarr;</span></a>
            @else
                <p class="profile-help">Sign in with a customer account to request a service.</p>
            @endif
        @else
            <a class="button" href="{{ route('customer.requests.create', $provider) }}">Log in to request a service <span>&rarr;</span></a>
        @endauth
    </article>
    @if($provider->services->isNotEmpty())<section class="public-profile-section"><div class="eyebrow muted">WHAT I CAN HELP WITH</div><h2>Services</h2><div class="public-service-grid">@foreach($provider->services as $service)<article><strong>{{ $service->title }}</strong><p>{{ $service->description ?: 'Ask me about this service.' }}</p><small>{{ $service->pricing_type==='quote'?'Request a quote':'From GH₵ '.number_format($service->price ?? 0,2).($service->pricing_type==='hourly'?' / hour':'') }}</small></article>@endforeach</div></section>@endif
    <section class="public-profile-section"><div class="eyebrow muted">COMMUNITY FEEDBACK</div><h2>Customer reviews</h2>@forelse($provider->reviews as $review)<article class="public-review"><strong>{{ str_repeat('★',$review->rating) }}{{ str_repeat('☆',5-$review->rating) }}</strong><small>{{ $review->customer->name }} · {{ $review->created_at->format('M Y') }}</small><p>{{ $review->comment ?: 'Customer left a rating.' }}</p></article>@empty<p class="profile-help">This professional has not received any reviews yet.</p>@endforelse</section>
</main>
</body>
</html>
