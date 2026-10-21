@extends('layouts.app.app')
@section('title','Explore local services | LocalPro')
@section('content')
@include('components.navbar.navbar')
<main class="requests-page directory-content service-directory">
    <header class="services-explore-hero" aria-labelledby="services-explore-title">
        <div class="services-explore-copy">
            <div class="eyebrow"><i data-lucide="map-pin" aria-hidden="true"></i> HO & VOLTA REGION</div>
            <h1 id="services-explore-title">{{ __('ui.explore_services') }}</h1>
            <p>From home repairs to personal care, start with the kind of help you need. Browse local service categories, then compare professional profiles and discuss the job directly.</p>
            <div class="services-explore-actions">
                <a class="button" href="{{ route('professionals.index') }}">Find a professional <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                <a class="services-provider-link" href="{{ route('providers.register') }}">Offer a service <i data-lucide="briefcase-business" aria-hidden="true"></i></a>
            </div>
        </div>
        <aside class="services-explore-aside" aria-label="A simple way to find local help">
            <span class="services-explore-aside-icon"><i data-lucide="compass" aria-hidden="true"></i></span>
            <span class="services-explore-aside-label">A GOOD PLACE TO START</span>
            <strong>Find the right help, one step at a time.</strong>
            <span class="services-explore-aside-step"><i data-lucide="check" aria-hidden="true"></i> Choose a service category</span>
            <span class="services-explore-aside-step"><i data-lucide="check" aria-hidden="true"></i> Review local professional profiles</span>
            <span class="services-explore-aside-step"><i data-lucide="check" aria-hidden="true"></i> Talk through the job details</span>
        </aside>
    </header>

    <nav class="services-town-links" aria-label="Find professionals by town">
        <div><span class="services-town-label">START NEAR YOU</span><strong>Browse professionals by town</strong></div>
        <div class="services-town-list">@foreach(['Ho', 'Hohoe', 'Keta', 'Aflao'] as $town)
            <a href="{{ route('professionals.index', ['city' => $town]) }}"><i data-lucide="map-pin" aria-hidden="true"></i>{{ $town }}<i data-lucide="arrow-right" aria-hidden="true"></i></a>
        @endforeach</div>
    </nav>

    <section class="services-category-section" aria-labelledby="services-category-title">
        <div class="services-section-heading">
            <div><span class="services-section-kicker">FIND YOUR SERVICE</span><h2 id="services-category-title">{{ __('ui.browse_categories') }}</h2><p>Choose a category to see local professionals and learn more about the services they offer.</p></div>
            <span class="services-category-count"><strong>{{ $categories->count() }}</strong> {{ \Illuminate\Support\Str::plural('category', $categories->count()) }}</span>
        </div>
        <div class="category-grid">@forelse($categories as $category)
            <x-service-card.service-card :category="$category" />
        @empty
            <div class="services-empty-state"><span><i data-lucide="briefcase-business" aria-hidden="true"></i></span><div><strong>Service categories are being set up.</strong><p>Please check back soon, or browse all professionals in the meantime.</p></div><a href="{{ route('professionals.index') }}">Browse professionals <i data-lucide="arrow-right" aria-hidden="true"></i></a></div>
        @endforelse</div>
    </section>

    <section class="services-discovery-guide" aria-labelledby="services-guide-title">
        <div class="services-guide-heading"><span class="services-section-kicker">A CLEARER WAY TO CONNECT</span><h2 id="services-guide-title">What happens after you choose?</h2><p>Take a moment to compare your options and agree on the details with the professional before work begins.</p></div>
        <div class="services-guide-steps">
            <article><span>01</span><i data-lucide="user-round" aria-hidden="true"></i><h3>Explore profiles</h3><p>Review each professional’s services, experience, reviews, and verification information where available.</p></article>
            <article><span>02</span><i data-lucide="message-circle" aria-hidden="true"></i><h3>Discuss the job</h3><p>Share what you need, the location, timing, and any questions before making an arrangement.</p></article>
            <article><span>03</span><i data-lucide="handshake" aria-hidden="true"></i><h3>Agree on details</h3><p>Confirm the scope, price, and schedule directly with the professional before work starts.</p></article>
        </div>
    </section>

    <section class="services-provider-cta" aria-labelledby="services-provider-title">
        <span class="services-provider-cta-icon"><i data-lucide="briefcase-business" aria-hidden="true"></i></span>
        <div><span class="services-section-kicker">FOR LOCAL PROFESSIONALS</span><h2 id="services-provider-title">Ready to share your skills?</h2><p>Create a profile to introduce your services to people looking for local help.</p></div>
        <a class="button" href="{{ route('providers.register') }}">Join LocalPro <i data-lucide="arrow-right" aria-hidden="true"></i></a>
    </section>
</main>
@include('components.footer.footer')
@endsection
