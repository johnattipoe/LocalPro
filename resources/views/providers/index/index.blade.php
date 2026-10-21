<!doctype html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Find a professional | LocalPro</title>
    @vite(['resources/css/app.css','resources/ts/app/app.ts'])
</head>
<body class="directory-page professionals-directory-page">
    @include('components.navbar.navbar')
    <main class="requests-page directory-content">
        <header class="professionals-directory-hero">
            <div class="professionals-directory-copy">
                <span class="professionals-directory-kicker"><i data-lucide="map-pin" aria-hidden="true"></i> LOCAL PROFESSIONALS · VOLTA REGION</span>
                <h1>Find the right professional for your job.</h1>
                <p>Explore approved local profiles, compare services and starting prices, and contact a professional to talk through the details.</p>
                <div class="professionals-directory-actions">
                    <a class="button" href="#professional-filters">Browse professionals <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                    <a href="{{ route('services.index') }}">Explore services <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="professionals-directory-points">
                    <span><i data-lucide="shield-check" aria-hidden="true"></i> Approved profiles</span>
                    <span><i data-lucide="map-pin" aria-hidden="true"></i> Ho and nearby towns</span>
                    <span><i data-lucide="message-circle" aria-hidden="true"></i> Contact professionals directly</span>
                </div>
            </div>
            <aside class="professionals-directory-highlight" aria-label="Search overview">
                <span class="directory-highlight-icon"><i data-lucide="users-round" aria-hidden="true"></i></span>
                <span class="directory-highlight-label">YOUR LOCAL SEARCH</span>
                <strong>{{ number_format($providers->total()) }}</strong>
                <span class="directory-highlight-caption">approved {{ \Illuminate\Support\Str::plural('professional', $providers->total()) }} to explore</span>
                <div><i data-lucide="check" aria-hidden="true"></i> Filter by service, town, rating, and price</div>
            </aside>
        </header>

        <section class="directory-category-shortcuts" aria-labelledby="directory-category-title">
            <div><span class="directory-section-kicker">START WITH A SERVICE</span><h2 id="directory-category-title">Browse popular categories</h2></div>
            <a class="directory-all-categories" href="{{ route('services.index') }}">See all services <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            <div class="directory-category-chips">
                @foreach($categories->take(6) as $category)
                    <a href="{{ route('services.show', $category->slug) }}"><i data-lucide="{{ in_array($category->icon, ['water','bolt','hammer','wrench','brush','phone','snowflake','sparkles','scissors','needle','camera','tool'], true) ? $category->icon : 'tool' }}" aria-hidden="true"></i>{{ $category->name }}<i data-lucide="arrow-right" aria-hidden="true"></i></a>
                @endforeach
            </div>
        </section>

        <section class="directory-search-section" aria-labelledby="directory-search-title" id="professional-filters">
            <div class="directory-search-heading">
                <span class="directory-section-icon"><i data-lucide="settings" aria-hidden="true"></i></span>
                <div><span class="directory-section-kicker">REFINE YOUR SEARCH</span><h2 id="directory-search-title">Find a professional</h2><p>Choose the details that matter for your job.</p></div>
            </div>
            <form data-provider-search class="provider-filters directory-filters" method="GET" action="{{ route('professionals.index') }}">
                <label><span><i data-lucide="search" aria-hidden="true"></i> Service or skill</span>
                    <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="e.g. electrician"></label>
                <label><span><i data-lucide="briefcase-business" aria-hidden="true"></i> Category</span>
                    <select name="category">
                        <option value="">All categories</option>@foreach($categories as $category)
                        <option value="{{ $category->name }}" @selected(($filters['category'] ?? '')===$category->name)>{{ $category->name }}</option>@endforeach
                    </select>
                </label>
                <label><span><i data-lucide="map-pin" aria-hidden="true"></i> Town</span>
                    <select name="city">
                        <option value="">All towns</option>@foreach(['Ho','Hohoe','Keta','Aflao'] as $city)
                        <option value="{{ $city }}" @selected(($filters['city'] ?? '')===$city)>{{ $city }}</option>@endforeach
                    </select>
                </label>
                <label><span><i data-lucide="star" aria-hidden="true"></i> Minimum rating</span>
                    <select name="min_rating">
                        <option value="">Any rating</option>@foreach([3,4,4.5] as $rating)
                        <option value="{{ $rating }}" @selected(($filters['min_rating'] ?? '')==$rating)>{{ $rating }}+ stars</option>@endforeach
                    </select>
                </label>
                <label><span><i data-lucide="credit-card" aria-hidden="true"></i> Maximum starting price</span>
                    <input type="number" name="max_price" min="0" step="1" value="{{ $filters['max_price'] ?? '' }}" placeholder="GH&#8373;"></label>
                <button class="button" type="submit"><i data-lucide="search" aria-hidden="true"></i> Search professionals</button>
            </form>
            @if(!empty($filters['q']) || !empty($filters['category']) || !empty($filters['city']) || !empty($filters['min_rating']) || isset($filters['max_price']))
                <div class="directory-active-filters" aria-label="Active filters">
                    <strong><i data-lucide="search" aria-hidden="true"></i> Filters applied</strong>
                    @if(!empty($filters['q']))<span>Service: {{ $filters['q'] }}</span>@endif
                    @if(!empty($filters['category']))<span>{{ $filters['category'] }}</span>@endif
                    @if(!empty($filters['city']))<span><i data-lucide="map-pin" aria-hidden="true"></i>{{ $filters['city'] }}</span>@endif
                    @if(!empty($filters['min_rating']))<span><i data-lucide="star" aria-hidden="true"></i>{{ $filters['min_rating'] }}+ stars</span>@endif
                    @if(isset($filters['max_price']))<span>Up to GH₵ {{ number_format((float) $filters['max_price'], 2) }}</span>@endif
                    <a href="{{ route('professionals.index') }}">Clear all</a>
                </div>
            @endif
        </section>

        <section class="directory-provider-results" aria-labelledby="directory-results-title">
            <div class="directory-results-heading">
                <div><span class="directory-section-kicker">YOUR LOCAL OPTIONS</span><h2 id="directory-results-title">{{ !empty($filters['q']) || !empty($filters['category']) ? 'Matching professionals' : 'Professionals you can contact' }}</h2><p>Review each profile, compare what they offer, and contact someone who fits your needs.</p></div>
                <span class="directory-results-total"><strong>{{ number_format($providers->total()) }}</strong>{{ \Illuminate\Support\Str::plural('professional', $providers->total()) }} found</span>
            </div>
            <div class="provider-grid directory-provider-grid">
                @forelse($providers as $provider)
                    <x-provider-card.provider-card :provider="$provider" />
                @empty
                    <div class="provider-empty directory-empty">
                        <div class="empty-icon"><i data-lucide="search" aria-hidden="true"></i></div>
                        <div><strong>No professionals match those filters yet.</strong><p>Try another service, town, rating, or price range, or clear the filters to browse all available profiles.</p></div>
                        <a href="{{ route('professionals.index') }}">Clear filters <b><i data-lucide="arrow-right" aria-hidden="true"></i></b></a>
                    </div>
                @endforelse
            </div>
            <div class="directory-pagination">{{ $providers->links() }}</div>
        </section>

        <aside class="directory-confidence-note">
            <span><i data-lucide="shield-check" aria-hidden="true"></i></span>
            <div><strong>Take time to choose with confidence</strong><p>Read profile details and reviews, ask questions, and agree on the work, schedule, and price with your chosen professional before work begins.</p></div>
            <a href="{{ route('how-it-works') }}">How LocalPro works <i data-lucide="arrow-right" aria-hidden="true"></i></a>
        </aside>
    </main>
    @include('components.footer.footer')
</body>
</html>
