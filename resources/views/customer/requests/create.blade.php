<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request {{ $provider->profession }} service | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body>
<header class="topbar"><a class="brand" href="{{ route('home') }}"><span class="brand-mark">&#10003;</span><span>local<span>pro</span><small>VOLTA REGION</small></span></a><a class="login-link" href="{{ route('customer.requests.index') }}">My requests</a></header>
<main class="request-page">
    <a class="text-link" href="{{ route('providers.show', $provider) }}">&larr; Back to {{ $provider->user->name }}'s profile</a>
    <div class="request-layout">
        <section class="request-form-card">
            <div class="eyebrow muted">YOUR SERVICE REQUEST</div>
            <h1>Tell {{ $provider->user->name }} what you need.</h1>
            <p class="request-intro">Share a few details so they can understand the job and get back to you with a quote.</p>
            <form data-booking-form method="POST" action="{{ route('customer.requests.store') }}">
                @csrf
                <input type="hidden" name="provider_profile_id" value="{{ $provider->id }}">
                @if($category)<input type="hidden" name="service_category_id" value="{{ $category->id }}">@endif
                <div class="form-row">
                    <label for="description">What needs to be done?</label>
                    <textarea id="description" name="description" rows="5" minlength="15" maxlength="3000" required placeholder="Describe the issue, what you have noticed, and any useful details...">{{ old('description') }}</textarea>
                    @error('description')<small class="error-message">{{ $message }}</small>@enderror
                </div>
                <div class="form-row">
                    <label for="city">Where is the job?</label>
                    <select id="city" name="city" required>
                        <option value="Ho" @selected(old('city', auth()->user()->city ?: 'Ho') === 'Ho')>Ho</option>
                        <option value="Hohoe" @selected(old('city') === 'Hohoe')>Hohoe</option>
                        <option value="Keta" @selected(old('city') === 'Keta')>Keta</option>
                        <option value="Aflao" @selected(old('city') === 'Aflao')>Aflao</option>
                    </select>
                    @error('city')<small class="error-message">{{ $message }}</small>@enderror
                </div>
                <div class="form-row">
                    <label for="address">Area or landmark <span>(optional)</span></label>
                    <input id="address" name="address" value="{{ old('address') }}" maxlength="255" placeholder="For example, near Ho Central Market">
                    @error('address')<small class="error-message">{{ $message }}</small>@enderror
                </div>
                <div class="form-row">
                    <label for="preferred_at">When would you like the work done? <span>(optional)</span></label>
                    <input id="preferred_at" type="date" name="preferred_at" min="{{ now()->toDateString() }}" value="{{ old('preferred_at') }}">
                    @error('preferred_at')<small class="error-message">{{ $message }}</small>@enderror
                </div>
                @if($errors->has('provider_profile_id') || $errors->has('service_category_id'))<div class="error-message">This professional is no longer available for requests. Return to the service list and choose another professional.</div>@endif
                <div class="request-note"><span>&#10003;</span><p>There is no charge to send a request. You and the professional agree on the price before work begins.</p></div>
                <button class="button form-submit" type="submit">Send service request <span>&rarr;</span></button>
            </form>
        </section>
        <aside class="request-provider-card">
            <div class="profile-avatar">{{ mb_substr($provider->user->name, 0, 1) }}</div>
            <span class="verified-filter">&#10003; VERIFIED</span>
            <h2>{{ $provider->user->name }}</h2><p>{{ $provider->profession }}</p>
            <div class="provider-info-line">Ho, Volta Region</div>
            <div class="provider-info-line">&#9733; {{ number_format($provider->average_rating, 1) }} rating from {{ $provider->review_count }} reviews</div>
            <div class="provider-info-line">Starting at @if($provider->starting_price)GH&#8373; {{ number_format($provider->starting_price, 2) }}@else quote on request @endif</div>
            <p class="privacy-note">Your contact details are shared only to coordinate this request.</p>
        </aside>
    </div>
</main>
</body>
</html>
