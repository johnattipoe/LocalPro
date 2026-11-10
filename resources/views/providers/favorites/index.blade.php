<!doctype html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Your saved professionals | LocalPro</title>
    @vite(['resources/css/app.css','resources/ts/app/app.ts'])
</head>
<body class="directory-page favorites-page">
    @include('components.navbar.navbar')
    <main class="requests-page directory-content">
        <header class="professionals-directory-hero">
            <div class="professionals-directory-copy">
                <span class="professionals-directory-kicker"><i data-lucide="heart" aria-hidden="true"></i> SAVED PROFESSIONALS</span>
                <h1>Your saved professionals</h1>
                <p>Professionals you've bookmarked for quick access. Pick up your search right where you left off.</p>
                <div class="professionals-directory-actions">
                    <a class="button" href="{{ route('professionals.index') }}">Browse more professionals <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
            <aside class="professionals-directory-highlight" aria-label="Saved overview">
                <span class="directory-highlight-icon"><i data-lucide="bookmark" aria-hidden="true"></i></span>
                <span class="directory-highlight-label">YOUR FAVORITES</span>
                <strong>{{ number_format($providers->total()) }}</strong>
                <span class="directory-highlight-caption">{{ \Illuminate\Support\Str::plural('saved professional', $providers->total()) }}</span>
                <div><i data-lucide="heart" aria-hidden="true"></i> Tap the heart again to remove</div>
            </aside>
        </header>

        <section class="directory-provider-results" aria-labelledby="directory-results-title">
            <div class="directory-results-heading">
                <div><span class="directory-section-kicker">YOUR LOCAL OPTIONS</span><h2 id="directory-results-title">Saved professionals</h2><p>Review your bookmarked providers and contact them when you're ready.</p></div>
                <span class="directory-results-total"><strong>{{ number_format($providers->total()) }}</strong>{{ \Illuminate\Support\Str::plural('professional', $providers->total()) }} saved</span>
            </div>
            <div class="provider-grid directory-provider-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,25rem),25rem));gap:18px">
                @forelse($providers as $provider)
                    <x-provider-card.provider-card :provider="$provider" />
                @empty
                    <div class="provider-empty directory-empty">
                        <div class="empty-icon"><i data-lucide="heart" aria-hidden="true"></i></div>
                        <div><strong>No saved professionals yet.</strong><p>Tap the heart on any provider card to bookmark them here.</p></div>
                        <a href="{{ route('professionals.index') }}">Browse professionals <b><i data-lucide="arrow-right" aria-hidden="true"></i></b></a>
                    </div>
                @endforelse
            </div>
            <div class="directory-pagination">{{ $providers->links() }}</div>
        </section>
    </main>
    @include('components.footer.footer')
</body>
</html>