@extends('layouts.customer-portal.customer-portal')
@section('title', 'Service request')
@section('content')
<div class="cp-page-heading cp-request-page-heading">
    <div>
        <span class="cp-eyebrow">REQUEST #{{ str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT) }}</span>
        <h1>{{ $serviceRequest->category->name ?? $serviceRequest->provider->profession }} request</h1>
        <p>Sent {{ $serviceRequest->created_at->format('F j, Y') }}</p>
    </div>
    <div class="cp-request-heading-actions">
        <span class="cp-status cp-status-{{ str_replace('_', '-', $serviceRequest->status) }}">{{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}</span>
        <a class="cp-button" href="{{ route('customer.requests.index') }}">All requests</a>
    </div>
</div>
<section class="cp-request-legacy">
    @if(session('status'))<div class="flash-note">&#10003; {{ session('status') }}</div>@endif
    @error('payment')<div class="cp-flash cp-flash-error" role="alert">{{ $message }}</div>@enderror
    <div class="request-detail-grid">
        <section class="request-detail-card">
            <h2>{{ __('ui.job_details') }}</h2>
            <p class="detail-description">{{ $serviceRequest->description }}</p>
            <div class="detail-meta">
                <span><small>{{ __('ui.location') }}</small><strong>{{ $serviceRequest->city }}@if($serviceRequest->address), {{ $serviceRequest->address }}@endif</strong></span>
                <span><small>{{ __('ui.preferred_date') }}</small><strong>{{ $serviceRequest->booking?->scheduled_at?->format('M j, Y g:i A') ?? $serviceRequest->preferred_at?->format('M j, Y') ?? 'Flexible' }}</strong></span>
                @if($serviceRequest->booking?->quoted_price)<span><small>PROVIDER QUOTE</small><strong>GH₵ {{ number_format($serviceRequest->booking->quoted_price, 2) }}</strong></span>@endif
                @if($serviceRequest->booking?->final_price)<span><small>FINAL PRICE</small><strong>GH₵ {{ number_format($serviceRequest->booking->final_price, 2) }}</strong></span>@endif
            </div>
            <div class="request-next-step">
                <span>&#10003;</span>
                <p><strong>{{ __('ui.what_happens_next') }}</strong><br>The professional will review your request and contact you to discuss the details, price, and timing.</p>
            </div>

            <div class="request-action-row">
                <a class="button message-cta" href="{{ route('messages.show', $serviceRequest) }}">Message your professional →</a>
                @if($serviceRequest->status === 'completed' && ($serviceRequest->booking?->final_price ?? 0) > 0 && config('services.paystack.secret_key') && ! $serviceRequest->booking->payments()->where('status', 'paid')->exists())
                    <form method="POST" action="{{ route('customer.payments.initialize', $serviceRequest) }}">
                        @csrf
                        <button class="button message-cta" type="submit">Pay GH₵ {{ number_format($serviceRequest->booking->final_price, 2) }} securely</button>
                    </form>
                @elseif($serviceRequest->booking && $serviceRequest->booking->payments()->where('status', 'paid')->exists())
                    <p class="profile-help" role="status">Payment confirmed · GH₵ {{ number_format($serviceRequest->booking->final_price, 2) }}</p>
                @elseif($serviceRequest->status === 'completed' && ($serviceRequest->booking?->final_price ?? 0) > 0)
                    <p class="profile-help">Online checkout is not available yet. Contact LocalPro support for help with payment options.</p>
                @endif
            </div>

            @if(in_array($serviceRequest->status, ['pending', 'accepted']))
                <form class="cancel-request" method="POST" action="{{ route('customer.requests.cancel', $serviceRequest) }}">
                    @csrf
                    @method('PATCH')
                    <button class="button button-secondary" type="submit">{{ __('ui.cancel_request') }}</button>
                </form>
            @endif
            @if($serviceRequest->status === 'completed' && ! $serviceRequest->booking?->review)
                <a class="button button-secondary message-cta" href="{{ route('customer.reviews.create', $serviceRequest) }}">{{ __('ui.leave_review') }}</a>
            @elseif($serviceRequest->booking?->review)
                <p class="profile-help">You reviewed this professional · {{ $serviceRequest->booking->review->rating }}/5</p>
            @endif
        </section>
        <aside class="request-detail-card pro-summary">
            <div class="eyebrow muted">YOUR PROFESSIONAL</div>
            <div class="profile-avatar">{{ mb_substr($serviceRequest->provider->user->name, 0, 1) }}</div>
            <h2>{{ $serviceRequest->provider->user->name }}</h2>
            <p>{{ $serviceRequest->provider->profession }}</p>
            <span class="verified-filter">&#10003; IDENTITY VERIFIED</span>
            <a class="text-link" href="{{ route('providers.show', $serviceRequest->provider) }}">View profile &rarr;</a>
            @if(in_array($serviceRequest->status, ['accepted', 'completed'], true))
                <a class="text-link" href="{{ route('contact', ['topic' => 'dispute', 'service_request_id' => $serviceRequest->id]) }}">Report a booking problem</a>
            @endif
        </aside>
    </div>
</section>
@endsection
