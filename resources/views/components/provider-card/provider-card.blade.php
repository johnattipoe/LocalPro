@props(['provider'])
@php
    $providerPhotoUrl = $provider->profile_photo_path
        ? \Illuminate\Support\Facades\Storage::disk($provider->profile_photo_disk ?: 'public')->url($provider->profile_photo_path)
        : null;
    $activeServices = $provider->services->where('is_active', true)->take(4);
    $memberSince = $provider->created_at?->format('Y');
    $providerCity = $provider->city ?: ($provider->user->city ?: 'Ho');
@endphp

<article class="provider-card provider-feature-card" @if(request()->routeIs('professionals.index', 'services.show', 'home')) style="width:400px;max-width:100%;height:auto;min-height:356px" @endif>
    <div class="provider-feature-cover" aria-label="{{ $provider->profession }} professional at work">
        <span class="provider-feature-verified"><i data-lucide="shield-check" aria-hidden="true"></i> VERIFIED</span>
        <span class="provider-feature-status{{ $provider->accepting_requests ? ' is-available' : '' }}">
            <i aria-hidden="true"></i><span><b>{{ $provider->accepting_requests ? 'Accepting requests' : 'Not accepting requests' }}</b><small>{{ $provider->accepting_requests ? 'Available for new work' : 'Check back later' }}</small></span>
        </span>
        <span class="provider-feature-avatar{{ $providerPhotoUrl ? ' has-photo' : '' }}">
            @if($providerPhotoUrl)<img src="{{ $providerPhotoUrl }}" alt="{{ $provider->user->name }}">@else<span aria-hidden="true">{{ mb_strtoupper(mb_substr($provider->user->name, 0, 1)) }}</span>@endif
        </span>
        @auth
            @php($isFavorited = auth()->user()->favorites()->where('provider_profile_id', $provider->id)->exists())
            <form class="provider-feature-favorite-toggle" method="POST" action="{{ route('providers.favorite.toggle', $provider) }}" data-favorite-toggle>
                @csrf
                <input type="hidden" name="is_favorited" value="{{ $isFavorited ? '1' : '0' }}">
                <button type="button" class="provider-feature-favorite-btn{{ $isFavorited ? ' is-favorited' : '' }}" aria-label="{{ $isFavorited ? 'Remove from saved' : 'Save to favorites' }}" data-favorite-btn>
                    <i data-lucide="{{ $isFavorited ? 'heart' : 'heart' }}" aria-hidden="true"></i>
                    <span>{{ $isFavorited ? 'Saved' : 'Save' }}</span>
                </button>
            </form>
        @endauth
    </div>

    <div class="provider-feature-content">
        <div class="provider-feature-heading">
            <div class="provider-feature-identity">
                <h3>{{ $provider->user->name }}</h3>
                <p>{{ $provider->profession }}</p>
            </div>
            <a class="provider-feature-contact" href="{{ route('providers.show', $provider) }}#contact"><i data-lucide="message-square-text" aria-hidden="true"></i> Contact</a>
        </div>

        <div class="provider-feature-facts">
            @if((int) $provider->review_count > 0)
                <span class="provider-feature-rating"><i data-lucide="star" aria-hidden="true"></i><b>{{ number_format((float) $provider->average_rating, 1) }}</b><small>({{ $provider->review_count }} {{ \Illuminate\Support\Str::plural('review', $provider->review_count) }})</small></span>
            @else
                <span class="provider-feature-new"><i data-lucide="sparkles" aria-hidden="true"></i>New provider <small>No reviews yet</small></span>
            @endif
            <span><i data-lucide="map-pin" aria-hidden="true"></i>{{ $providerCity }}, {{ $provider->region ?: 'Volta Region' }}</span>
            @if($memberSince)<span><i data-lucide="calendar-days" aria-hidden="true"></i>Member since {{ $memberSince }}</span>@endif
        </div>

        <div class="provider-feature-pills">
            @if($provider->service_area)<span><i data-lucide="map" aria-hidden="true"></i>Serves {{ $provider->service_area }}</span>@endif
            <span><i data-lucide="shield-check" aria-hidden="true"></i>Identity checked</span>
            <span><i data-lucide="{{ $provider->service_mode === 'customer_location' ? 'truck' : ($provider->service_mode === 'provider_location' ? 'store' : 'repeat-2') }}" aria-hidden="true"></i>{{ $provider->service_mode === 'customer_location' ? 'Travels to customers' : ($provider->service_mode === 'provider_location' ? 'At business location' : 'Both service options') }}</span>
            @if($provider->accepting_requests)<span class="is-accepting"><i data-lucide="calendar-check" aria-hidden="true"></i>Accepting requests</span>@endif
        </div>

        @if($provider->bio)<p class="provider-feature-bio">{{ \Illuminate\Support\Str::limit($provider->bio, 220) }}</p>@endif

        <div class="provider-feature-services">
            <span class="provider-feature-services-icon"><i data-lucide="scissors" aria-hidden="true"></i></span>
            <div><small>Services</small><p>{{ $activeServices->isNotEmpty() ? $activeServices->pluck('title')->implode(', ') : $provider->profession }}</p></div>
        </div>

        <div class="provider-feature-actions">
            <div class="provider-feature-price"><span class="provider-feature-price-icon"><i data-lucide="circle-dollar-sign" aria-hidden="true"></i></span><span><small>Starting from</small><b>@if($provider->starting_price)GH₵ {{ number_format((float) $provider->starting_price, 2) }}@else Request a quote @endif</b></span></div>
            <a class="provider-feature-profile" href="{{ route('providers.show', $provider) }}">View profile <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            @if($provider->service_mode !== 'customer_location')
                <a class="provider-feature-directions" href="{{ $provider->directions_url }}" target="_blank" rel="noopener noreferrer" aria-label="Get directions to {{ $provider->user->name }}"><i data-lucide="map-pinned" aria-hidden="true"></i>Get directions</a>
            @else
                <span class="provider-feature-directions is-note"><i data-lucide="truck" aria-hidden="true"></i>Travels to you</span>
            @endif
        </div>

        <div class="provider-feature-trust"><span><i data-lucide="circle-check" aria-hidden="true"></i>Verified provider</span><span><i data-lucide="star" aria-hidden="true"></i>{{ number_format((float) $provider->average_rating, 1) }} rating</span><span><i data-lucide="message-circle" aria-hidden="true"></i>{{ $provider->review_count }} reviews</span></div>
    </div>
</article>
