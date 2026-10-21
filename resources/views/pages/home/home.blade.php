<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f2">
    <meta name="description" content="Find trusted local professionals in Ho and across the Volta Region.">
    <title>LocalPro — Good people. Good work.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
</head>
<body id="top" class="home-page">
<header class="topbar">
    <a class="brand" href="{{ route('home') }}">
        <span class="brand-mark">
            <img class="brand-logo-mark" src="{{ asset('logo/localpro-mark.png') }}" alt="">
        </span>
        <span>local<span>pro</span>
        <small>VOLTA REGION</small></span>
    </a>

    <nav class="main-nav">
        <a class="active" href="#services"><i data-lucide="briefcase-business" aria-hidden="true"></i> {{ __('ui.services') }}</a>
        <a href="#how"><i data-lucide="circle-help" aria-hidden="true"></i> {{ __('ui.how_it_works') }}</a>
        <a href="#providers"><i data-lucide="search" aria-hidden="true"></i> {{ __('ui.find_professional') }}</a>
    </nav>

    <div class="top-actions">
        @include('components.language-picker.language-picker')
        <span class="location-chip">
            <i data-lucide="map-pin" aria-hidden="true"></i> Ho, Volta Region
        </span>
        @auth
            @include('components.navbar.account-menu')
        @else
            <a class="login-link" href="{{ route('login') }}">{{ __('ui.log_in') }}</a>
            <a class="button button-small" href="{{ route('register') }}">{{ __('ui.sign_up') }}</a>
        @endauth
    </div>
</header>
<main>
    @if(session('status'))<div class="flash-note">  {{ session('status') }}</div>@endif
    <section class="hero">
        <div class="hero-copy">
            <div class="eyebrow"><i data-lucide="sparkles" aria-hidden="true"></i> {{ __('ui.home_eyebrow') }}</div>
            <h1>{{ __('ui.hero_title_first') }}<br>{{ __('ui.hero_title_second') }}</h1>
            <p>{{ __('ui.hero_description') }}</p>
            <form class="search-panel" action="{{ route('professionals.index') }}" method="GET">
                <label class="search-field"><i data-lucide="search" aria-hidden="true"></i><span><small>{{ __('ui.search_placeholder') }}</small><input type="search" name="q" value="{{ request('q') }}" placeholder="Try &quot;fix a leaking tap&quot;"></span></label>
                <span class="search-divider"></span>
                <label class="search-field search-location"><i data-lucide="map-pin" aria-hidden="true"></i><span><small>{{ __('ui.home_search_area') }}</small><select name="city"><option value="Ho" @selected(request('city', 'Ho') === 'Ho')>Ho, Volta Region</option><option value="Hohoe" @selected(request('city') === 'Hohoe')>Hohoe, Volta Region</option><option value="Keta" @selected(request('city') === 'Keta')>Keta, Volta Region</option><option value="Aflao" @selected(request('city') === 'Aflao')>Aflao, Volta Region</option></select></span></label>
                <button class="button search-button" type="submit"><i data-lucide="search" aria-hidden="true"></i>{{ __('ui.home_search_action') }}</button>
            </form>
            <div class="popular"><span>{{ __('ui.home_popular') }}</span><a href="{{ route('home', ['q' => 'Plumber']) }}">Plumber</a><a href="{{ route('home', ['q' => 'Electrician']) }}">Electrician</a><a href="{{ route('home', ['q' => 'AC technician']) }}">AC technician</a></div>
            <div class="hero-note"><span class="people-dots"><i>K</i><i>A</i><i>S</i><b>+</b></span><span><strong>{{ __('ui.home_local_trust') }}</strong><small>{{ __('ui.home_community_pros') }}</small></span><span class="note-rule"></span><span><strong class="rating">4.8 <i data-lucide="star" aria-hidden="true"></i></strong><small>{{ __('ui.home_verified_reviews') }}</small></span></div>
        </div>
        <div class="hero-art" aria-label="Illustration of a local service professional at work" role="img"><div class="art-circle"></div><div class="art-orbit"></div><div class="art-spark"><i data-lucide="sparkles" aria-hidden="true"></i></div><div class="worker"><div class="worker-head"><div class="worker-hair"></div><div class="worker-face"><i></i><i></i><b></b></div></div><div class="worker-neck"></div><div class="worker-body"><span></span></div><div class="worker-arm arm-one"></div><div class="worker-arm arm-two"></div><div class="worker-box"><i></i></div></div><div class="float-tag verified-tag"><span><i data-lucide="shield-check" aria-hidden="true"></i></span><div><strong>Verified professional</strong><small>Identity checked by LocalPro</small></div></div><div class="float-tag price-tag"><b><i data-lucide="circle-dollar-sign" aria-hidden="true"></i></b><div><strong>Clear quotes, no surprises</strong><small>Agree on price before work starts</small></div></div><span class="art-star"><i data-lucide="sparkles" aria-hidden="true"></i></span></div>
    </section>
    <section class="trust-row"><span><i data-lucide="shield-check" aria-hidden="true"></i>{{ __('ui.home_trust_verified') }}</span><b></b><span><i class="gold" data-lucide="credit-card" aria-hidden="true"></i>{{ __('ui.home_trust_prices') }}</span><b></b><span><i data-lucide="map-pin" aria-hidden="true"></i>{{ __('ui.home_trust_location') }}</span></section>
    <section class="section categories" id="services"><div class="section-heading"><div><div class="eyebrow muted">{{ __('ui.home_start_here') }}</div><h2>{{ __('ui.home_help_heading') }}</h2><p>{{ __("ui.home_help_intro") }}</p></div><a class="text-link" href="{{ route('services.index') }}">{{ __("ui.explore_services") }} <i data-lucide="arrow-right" aria-hidden="true"></i></a></div>
        <div class="category-grid">
            @forelse($categories as $category)
                <x-service-card.service-card :category="$category" />
            @empty
                <div class="category-empty">{{ __('ui.home_empty_services') }}</div>
            @endforelse
        </div>
    </section>
    <section class="section provider-section" id="providers"><div class="section-heading"><div><div class="eyebrow muted">{{ __('ui.home_provider_kicker') }}</div><h2>{{ __('ui.home_provider_heading') }}</h2><p>{{ __('ui.home_provider_intro') }}</p></div><span class="verified-filter"><i data-lucide="shield-check" aria-hidden="true"></i> Verified professionals</span></div>
        @if($providers->isNotEmpty())
            <div class="provider-grid">@foreach($providers as $provider)<x-provider-card.provider-card :provider="$provider" />@endforeach</div>
        @else
            <div class="provider-empty"><div class="empty-icon"><i data-lucide="users-round" aria-hidden="true"></i></div><div><strong>{{ __('ui.home_empty_providers') }}</strong><p>{{ __('ui.home_provider_joining') }}</p></div><a href="#join">{{ __('ui.home_join_question') }} <b>Join us <i data-lucide="arrow-right" aria-hidden="true"></i></b></a></div>
        @endif
    </section>
    <section class="how-section" id="how">
        <div class="eyebrow light">{{ __('ui.home_how_kicker') }}</div><h2>{{ __('ui.home_how_heading') }}</h2><p>{{ __('ui.home_how_intro') }}</p><div class="steps"><article><small>01</small><span><i data-lucide="search" aria-hidden="true"></i></span><h3>{{ __('ui.home_step_search') }}</h3><p>{{ __('ui.home_step_search_text') }}</p></article><i></i><article><small>02</small><span><i data-lucide="message-circle" aria-hidden="true"></i></span><h3>{{ __('ui.home_step_message') }}</h3><p>{{ __('ui.home_step_message_text') }}</p></article><i></i><article><small>03</small><span><i data-lucide="check" aria-hidden="true"></i></span><h3>{{ __('ui.home_step_done') }}</h3><p>{{ __('ui.home_step_done_text') }}</p></article></div><a href="#services">{{ __("ui.home_search_action") }} <i data-lucide="arrow-right" aria-hidden="true"></i></a></section>
    <section class="join-section" id="join"><div><div class="eyebrow muted">GOOD AT WHAT YOU DO?</div><h2>Your next customer is<br>closer than you think.</h2><p>Join local professionals building their reputation and finding steady work on LocalPro.</p><a class="button" href="{{ route('register', ['role' => 'provider']) }}">Become a LocalPro <i data-lucide="arrow-right" aria-hidden="true"></i></a></div><aside><span aria-hidden="true"><i data-lucide="shield-check"></i></span><strong>Good work deserves<br>to be seen.</strong><small>Start building your local reputation.</small></aside></section>
    <section class="section home-faq" id="answers"><div class="section-heading"><div><div class="eyebrow muted">HELPFUL DETAILS BEFORE YOU BOOK</div><h2>Common questions, clear answers.</h2><p>Know what to expect before you contact a professional.</p></div></div><div class="faq-list"><details><summary>What does a verified profile mean?</summary><p>It means the professional submitted identity documents and LocalPro approved the profile. Verification helps confirm who is using the account; it is not a guarantee of workmanship, price, or future conduct. Review the profile and discuss your job before you agree.</p></details><details><summary>How do I agree on a price?</summary><p>Describe the work in your request and use messages to discuss the scope, quote, materials, transport, and timing. Confirm the final amount with the professional before work begins. Indicative profile prices are starting points and may not cover every job.</p></details><details><summary>When should I leave a review?</summary><p>After the professional marks the job complete, you can rate and describe your experience from the request page. Reviews should be honest, respectful, and about the work you received.</p></details><details><summary>What if something goes wrong?</summary><p>Keep the conversation in your LocalPro account and contact support with the request number. The support team can review the case history and help both sides understand the next steps.</p></details><details><summary>Does LocalPro serve every town in the region?</summary><p>LocalPro is starting in Ho and nearby Volta Region communities. Availability depends on which professionals have completed their profiles and been approved in your area.</p></details></div><a class="text-link" href="{{ route('contact') }}">Contact LocalPro Support</a></section>
</main>
@include('components.footer.footer')
</body>
</html>
