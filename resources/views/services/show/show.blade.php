@extends('layouts.app.app')
@section('title', $category->name . ' professionals in Volta | LocalPro')
@section('content')
@include('components.navbar.navbar')
<main class="category-show directory-category category-results-page">
    <header class="category-results-hero">
        <div class="category-results-copy">
            <span class="category-results-kicker"><i data-lucide="map-pin" aria-hidden="true"></i> LOCAL SERVICES · VOLTA REGION</span>
            <h1>{{ $category->name }} in Ho and nearby</h1>
            <p>{{ $category->description ?: 'Find trusted local professionals for ' . strtolower($category->name) . ' services.' }} Compare approved profiles, ratings, and starting prices before choosing who to contact.</p>
            <div class="category-results-actions">
                <a class="button" href="{{ route('professionals.index', ['category' => $category->name]) }}">Browse all professionals <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                <a class="category-back-link" href="{{ route('services.index') }}"><i data-lucide="compass" aria-hidden="true"></i> All categories</a>
            </div>
        </div>
        <aside class="category-results-stat" aria-label="Category results">
            <span class="category-results-stat-icon"><i data-lucide="shield-check" aria-hidden="true"></i></span>
            <strong>{{ number_format($providers->total()) }}</strong>
            <span>verified {{ \Illuminate\Support\Str::plural('professional', $providers->total()) }}</span>
            <small>Serving Ho and Volta Region</small>
        </aside>
    </header>

    <section class="category-results-section" aria-labelledby="category-provider-title">
        <div class="category-results-section-heading">
            <div><span class="services-section-kicker">YOUR LOCAL OPTIONS</span><h2 id="category-provider-title">{{ $providers->total() ? 'Professionals you can contact' : 'Professionals joining LocalPro' }}</h2><p>Check profile details and reviews, then discuss the job directly with a professional.</p></div>
            @if($providers->total())<span class="category-results-count">{{ $providers->firstItem() }}–{{ $providers->lastItem() }} of {{ $providers->total() }}</span>@endif
        </div>

        @if($providers->isNotEmpty())
            <div class="provider-grid directory-provider-grid">@foreach($providers as $provider)<x-provider-card.provider-card :provider="$provider" />@endforeach</div>
            <div class="category-results-pagination">{{ $providers->links() }}</div>
        @else
            <div class="provider-empty directory-empty category-results-empty"><div class="empty-icon"><i data-lucide="search" aria-hidden="true"></i></div><div><strong>No verified {{ strtolower($category->name) }} professionals are listed yet.</strong><p>We’re growing the local network. Browse other services or invite a professional to join LocalPro.</p></div><div class="category-empty-actions"><a href="{{ route('services.index') }}">Explore other categories <i data-lucide="arrow-right" aria-hidden="true"></i></a><a href="{{ route('providers.register') }}">Register as a professional <i data-lucide="briefcase-business" aria-hidden="true"></i></a></div></div>
        @endif
    </section>

    <aside class="category-trust-note"><span><i data-lucide="shield-check" aria-hidden="true"></i></span><div><strong>Choose with confidence</strong><p>LocalPro marks providers whose identity and submitted details have been reviewed. Always confirm the job scope, schedule, and price before work starts.</p></div></aside>
</main>
@include('components.footer.footer')
@endsection
