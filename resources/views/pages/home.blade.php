<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f2">
    <meta name="description" content="Find trusted local professionals in Ho and across the Volta Region.">
    <title>LocalPro — Good people. Good work.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body id="top">
<header class="topbar">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark">✓</span><span>local<span>pro</span><small>VOLTA REGION</small></span></a>
    <nav class="main-nav"><a class="active" href="#services">Services</a><a href="#how">How it works</a><a href="#providers">Find a pro</a></nav>
    <div class="top-actions">
        <span class="location-chip"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>Ho, Volta Region</span>
        @auth
            <a class="login-link" href="{{ auth()->user()->role === 'customer' ? route('customer.requests.index') : (auth()->user()->role === 'provider' ? route('provider.dashboard') : route('admin.dashboard')) }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="login-link button-link" type="submit">Log out</button></form>
        @else
            <a class="login-link" href="{{ route('login') }}">Log in</a><a class="button button-small" href="{{ route('register') }}">Sign up</a>
        @endauth
    </div>
</header>
<main>
    @if(session('status'))<div class="flash-note">  {{ session('status') }}</div>@endif
    <section class="hero">
        <div class="hero-copy">
            <div class="eyebrow"><i></i> GOOD PEOPLE. GOOD WORK. RIGHT HERE.</div>
            <h1>Good work starts<br>with <em>the right person.</em></h1>
            <p>Find trusted local professionals in Ho and across the Volta Region. Tell us what you need; we'll help you find your person.</p>
            <form class="search-panel" action="{{ route('professionals.index') }}" method="GET">
                <label class="search-field"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><span><small>WHAT DO YOU NEED?</small><input type="search" name="q" value="{{ request('q') }}" placeholder="Try &quot;fix a leaking tap&quot;"></span></label>
                <span class="search-divider"></span>
                <label class="search-field search-location"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span><small>YOUR LOCATION</small><select name="city"><option value="Ho" @selected(request('city', 'Ho') === 'Ho')>Ho, Volta Region</option><option value="Hohoe" @selected(request('city') === 'Hohoe')>Hohoe, Volta Region</option><option value="Keta" @selected(request('city') === 'Keta')>Keta, Volta Region</option><option value="Aflao" @selected(request('city') === 'Aflao')>Aflao, Volta Region</option></select></span></label>
                <button class="button search-button" type="submit"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>Find a pro</button>
            </form>
            <div class="popular"><span>Popular:</span><a href="{{ route('home', ['q' => 'Plumber']) }}">Plumber</a><a href="{{ route('home', ['q' => 'Electrician']) }}">Electrician</a><a href="{{ route('home', ['q' => 'AC technician']) }}">AC technician</a></div>
            <div class="hero-note"><span class="people-dots"><i>K</i><i>A</i><i>S</i><b>+</b></span><span><strong>Local hands. Real trust.</strong><small>Professionals from your community</small></span><span class="note-rule"></span><span><strong class="rating">4.8 <b>★</b></strong><small>Verified customer reviews</small></span></div>
        </div>
        <div class="hero-art" aria-label="Illustration of a local service professional at work" role="img"><div class="art-circle"></div><div class="art-orbit"></div><div class="art-spark">✳</div><div class="worker"><div class="worker-head"><div class="worker-hair"></div><div class="worker-face"><i></i><i></i><b></b></div></div><div class="worker-neck"></div><div class="worker-body"><span></span></div><div class="worker-arm arm-one"></div><div class="worker-arm arm-two"></div><div class="worker-box"><i></i></div></div><div class="float-tag verified-tag"><span>✓</span><div><strong>Verified professional</strong><small>Identity checked by LocalPro</small></div></div><div class="float-tag price-tag"><b>GH₵</b><div><strong>Clear quotes, no surprises</strong><small>Agree on price before work starts</small></div></div><span class="art-star">✦</span></div>
    </section>
    <section class="trust-row"><span><i>✓</i>Professionals verified by our team</span><b></b><span><i class="gold">₵</i>Clear prices in Ghana cedis</span><b></b><span><i>◷</i>Local to Ho &amp; Volta Region</span></section>
    <section class="section categories" id="services"><div class="section-heading"><div><div class="eyebrow muted">A GOOD PLACE TO START</div><h2>What can we help with?</h2><p>Small fix or big project   find the right hands for it.</p></div><a class="text-link" href="#services">Explore all services <span>→</span></a></div>
        <div class="category-grid">
            @forelse($categories as $category)
                <a class="category-card" href="{{ route('services.show', $category->slug) }}"><span class="category-icon icon-{{ $loop->index % 6 }}">{{ ['🔧','✂','🪚','⚙','✳','🧰'][$loop->index % 6] }}</span><strong>{{ $category->name }}</strong><small>{{ $category->description }}</small><b>→</b></a>
            @empty
                <div class="category-empty">Our first services are getting ready. Check back soon.</div>
            @endforelse
        </div>
    </section>
    <section class="section provider-section" id="providers"><div class="section-heading"><div><div class="eyebrow muted">GOOD PEOPLE, GOOD WORK</div><h2>Locals you can count on.</h2><p>Meet verified professionals serving your area.</p></div><span class="verified-filter">  Verified professionals</span></div>
        @if($providers->isNotEmpty())
            <div class="provider-grid">@foreach($providers as $provider)<article class="provider-card"><div class="provider-cover"><span class="verified-badge">✓ VERIFIED</span><span class="provider-portrait">{{ mb_substr($provider->user->name, 0, 1) }}</span></div><div class="provider-body"><div class="provider-title"><div><strong>{{ $provider->user->name }}</strong><small>{{ $provider->profession }}</small></div><span>★ <b>{{ number_format($provider->average_rating, 1) }}</b> ({{ $provider->review_count }})</span></div><div class="provider-meta"><span>  {{ $provider->city }}</span><span>✓ Identity checked</span></div><div class="provider-bottom"><span><small>STARTING FROM</small><b>{{ $provider->starting_price ? 'GH₵ ' . number_format($provider->starting_price, 2) : 'Request a quote' }}</b></span><a href="{{ route('providers.show', $provider) }}">View profile →</a></div></div></article>@endforeach</div>
        @else
            <div class="provider-empty"><div class="empty-icon">✳</div><div><strong>We're building a trusted local network.</strong><p>Verified professionals from Ho and nearby towns will appear here as they join LocalPro.</p></div><a href="#join">Are you a local professional? <b>Join us →</b></a></div>
        @endif
    </section>
    <section class="how-section" id="how"><div class="eyebrow light">STRAIGHTFORWARD FROM START TO FINISH</div><h2>Getting it sorted feels good.</h2><p>From your first search to the final handshake, it's simple.</p><div class="steps"><article><small>01</small><span>◷</span><h3>Find your person</h3><p>Search by service and location. See who's nearby and available.</p></article><i></i><article><small>02</small><span>☏</span><h3>Talk it through</h3><p>Share what you need, compare quotes and agree on a time.</p></article><i></i><article><small>03</small><span>✓</span><h3>Get it done</h3><p>Your professional shows up, gets to work, and earns your review.</p></article></div><a href="#services">Let's find your pro <b> </b></a></section>
    <section class="join-section" id="join"><div><div class="eyebrow muted">GOOD AT WHAT YOU DO?</div><h2>Your next customer is<br>closer than you think.</h2><p>Join local professionals building their reputation and finding steady work on LocalPro.</p><a class="button" href="{{ route('register', ['role' => 'provider']) }}">Become a LocalPro <span>→</span></a></div><aside><span> </span><strong>Good work deserves<br>to be seen.</strong><small>Start building your local reputation.</small></aside></section>
    <section class="section home-faq" id="answers"><div class="section-heading"><div><div class="eyebrow muted">HELPFUL DETAILS BEFORE YOU BOOK</div><h2>Common questions, clear answers.</h2><p>Know what to expect before you contact a professional.</p></div></div><div class="faq-list"><details><summary>What does a verified profile mean?</summary><p>It means the professional submitted identity documents and LocalPro approved the profile. Verification helps confirm who is using the account; it is not a guarantee of workmanship, price, or future conduct. Review the profile and discuss your job before you agree.</p></details><details><summary>How do I agree on a price?</summary><p>Describe the work in your request and use messages to discuss the scope, quote, materials, transport, and timing. Confirm the final amount with the professional before work begins. Indicative profile prices are starting points and may not cover every job.</p></details><details><summary>When should I leave a review?</summary><p>After the professional marks the job complete, you can rate and describe your experience from the request page. Reviews should be honest, respectful, and about the work you received.</p></details><details><summary>What if something goes wrong?</summary><p>Keep the conversation in your LocalPro account and contact support with the request number. The support team can review the case history and help both sides understand the next steps.</p></details><details><summary>Does LocalPro serve every town in the region?</summary><p>LocalPro is starting in Ho and nearby Volta Region communities. Availability depends on which professionals have completed their profiles and been approved in your area.</p></details></div><a class="text-link" href="{{ route('contact') }}">Contact LocalPro Support</a></section>
</main>
@include('components.footer')
</body>
</html>
