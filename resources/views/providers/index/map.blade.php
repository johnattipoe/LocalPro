<!doctype html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Find professionals on a map | LocalPro</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @vite(['resources/css/app.css','resources/ts/app/app.ts'])
</head>
<body class="directory-page professionals-map-page">
    @include('components.navbar.navbar')
    <main class="requests-page directory-content">
        <header class="professionals-directory-hero">
            <div class="professionals-directory-copy">
                <span class="professionals-directory-kicker"><i data-lucide="map-pin" aria-hidden="true"></i> LOCAL PROFESSIONALS · VOLTA REGION</span>
                <h1>Find professionals nearby</h1>
                <p>View verified local professionals on a map and see where they operate.</p>
                <div class="professionals-directory-actions">
                    <a class="button" href="{{ route('professionals.index') }}?{{ http_build_query($filters) }}">Switch to list view <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
            <aside class="professionals-directory-highlight" aria-label="Search overview">
                <span class="directory-highlight-icon"><i data-lucide="users-round" aria-hidden="true"></i></span>
                <span class="directory-highlight-label">RESULTS ON MAP</span>
                <strong>{{ number_format($providers->total()) }}</strong>
                <span class="directory-highlight-caption">{{ \Illuminate\Support\Str::plural('professional', $providers->total()) }} in area</span>
                <div><i data-lucide="map-pin" aria-hidden="true"></i> Pin markers show available professionals</div>
            </aside>
        </header>

        <section class="directory-search-section" aria-labelledby="directory-search-title">
            <div class="directory-search-heading">
                <span class="directory-section-icon"><i data-lucide="settings" aria-hidden="true"></i></span>
                <div><span class="directory-section-kicker">REFINE YOUR SEARCH</span><h2 id="directory-search-title">Map filters</h2><p>Adjust the radius and location to find professionals near you.</p></div>
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
                <label><span><i data-lucide="circle-alert" aria-hidden="true"></i> Radius (km)</span>
                    <input type="number" name="radius" min="1" max="100" step="5" value="{{ $filters['radius'] ?? '20' }}" placeholder="20 km"></label>
                <label><span><i data-lucide="minimize-2" aria-hidden="true"></i> Max starting price</span>
                    <input type="number" name="max_price" min="0" step="1" value="{{ $filters['max_price'] ?? '' }}" placeholder="GH&#8373;"></label>
                <button type="button" class="button use-my-location" data-use-my-location><i data-lucide="crosshair" aria-hidden="true"></i> Use my location</button>
                <input type="hidden" name="lat" value="{{ $filters['lat'] ?? '' }}">
                <input type="hidden" name="lng" value="{{ $filters['lng'] ?? '' }}">
                <button type="submit" class="button"><i data-lucide="search" aria-hidden="true"></i> Update map</button>
            </form>
        </section>

        <section class="directory-map-section" aria-label="Professionals map" id="provider-map">
            <div id="map" style="height: 600px; width: 100%;"></div>
            <div class="directory-map-legend">
                <span><i data-lucide="map-pin" aria-hidden="true"></i> Professional location</span>
                <span><i data-lucide="crosshair" aria-hidden="true"></i> Your location</span>
            </div>
        </section>

        @if(!empty($filters['q']) || !empty($filters['category']) || !empty($filters['city']) || isset($filters['max_price']) || !empty($filters['radius']))
            <div class="directory-active-filters" aria-label="Active filters">
                <strong><i data-lucide="search" aria-hidden="true"></i> Filters applied</strong>
                @if(!empty($filters['q']))<span>Service: {{ $filters['q'] }}</span>@endif
                @if(!empty($filters['category']))<span>{{ $filters['category'] }}</span>@endif
                @if(!empty($filters['city']))<span><i data-lucide="map-pin" aria-hidden="true"></i>{{ $filters['city'] }}</span>@endif
                @if(!empty($filters['radius']))<span><i data-lucide="circle-alert" aria-hidden="true"></i>{{ $filters['radius'] }} km radius</span>@endif
                @if(isset($filters['max_price']))<span>Up to GH₵ {{ number_format((float) $filters['max_price'], 2) }}</span>@endif
                <a href="{{ route('professionals.index') }}">Clear all</a>
            </div>
        @endif
    </main>
    @include('components.footer.footer')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const providers = @json($mapProviders);
        const centerLat = {{ $filters['lat'] ?? '6.5903' }};
        const centerLng = {{ $filters['lng'] ?? '0.4722' }};
        const map = L.map('map').setView([centerLat, centerLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const userMarker = L.marker([centerLat, centerLng], {
            icon: L.divIcon({
                className: 'user-location-marker',
                html: '<div style="width:12px;height:12px;background:#2563eb;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3);"></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        }).addTo(map).bindPopup('Your location');

        const markers = [];
        providers.forEach((provider) => {
            const marker = L.marker([provider.location_latitude, provider.location_longitude]);
            marker.bindPopup(`
                <strong>${escapeHtml(provider.user.name)}</strong><br>
                ${escapeHtml(provider.profession)}<br>
                GH₵ ${Number(provider.starting_price).toLocaleString()}<br>
                <a href="${escapeHtml('{{ route(\'providers.show\', '__id__') }}'.replace('__id__', provider.id))}" target="_blank">View profile</a>
            `);
            marker.addTo(map);
            markers.push(marker);
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        document.querySelector('[data-use-my-location]')?.addEventListener('click', () => {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition((pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                map.setView([lat, lng], 13);
                userMarker.setLatLng([lat, lng]);
                document.querySelector('input[name="lat"]').value = lat;
                document.querySelector('input[name="lng"]').value = lng;
            });
        });
    </script>
</body>
</html>