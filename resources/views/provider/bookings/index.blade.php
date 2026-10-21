@extends('layouts.provider-portal.provider-portal')
@section('title', 'Service requests')
@section('content')
<div class="pp-page-heading"><div><span class="pp-eyebrow">{{ __('ui.service_provider') }}</span><h1>{{ __('ui.bookings') }} &amp; {{ __('ui.service_requests') }}</h1><p>Review customer needs, respond to new requests, and track each job through completion.</p></div><span class="pp-status pp-status-{{ $profile->verification_status }}">{{ ucfirst($profile->verification_status) }} {{ __('ui.profile') }}</span></div>
@if($profile->verification_status !== 'approved')<div class="pp-form-errors" role="status"><strong>Your provider profile is {{ $profile->verification_status }}.</strong><span>You can review requests, but must be verified before accepting jobs.</span><a href="{{ route('provider.verification.index') }}">Open verification</a></div>@endif
<section class="pp-panel pp-bookings-panel">
    <form class="pp-booking-filters" method="GET" action="{{ route('provider.bookings.index') }}">
        <label>Search requests<input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Customer, service, or town"></label>
        <label>Request status<select name="status"><option value="">All statuses</option>@foreach(['pending'=>'New','accepted'=>'Accepted','completed'=>'Completed','cancelled'=>'Cancelled','declined'=>'Declined'] as $key => $label)<option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>@endforeach</select></label>
        <button class="pp-accept-button" type="submit">Apply filters</button>
        @if(($filters['q'] ?? '') || ($filters['status'] ?? ''))<a class="pp-view-button" href="{{ route('provider.bookings.index') }}">Clear</a>@endif
    </form>
    <div class="pp-table-wrap"><table class="pp-table"><thead><tr><th>Service</th><th>{{ __('ui.customer') }}</th><th>Requested</th><th>{{ __('ui.town') }}</th><th>Quote</th><th>{{ __('ui.status') }}</th><th>{{ __('ui.action') }}</th></tr></thead><tbody>
        @forelse($requests as $serviceRequest)
            <tr><td><strong>{{ $serviceRequest->category?->name ?? $profile->profession }}</strong><small>#{{ str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT) }}</small></td><td><strong>{{ $serviceRequest->customer?->name ?? 'Customer' }}</strong><small>{{ $serviceRequest->customer?->phone ?? '' }}</small></td><td>{{ $serviceRequest->created_at->format('M j, Y') }}<small>{{ $serviceRequest->booking?->scheduled_at?->format('g:i A') ?? $serviceRequest->preferred_at?->format('g:i A') ?? 'Flexible time' }}</small></td><td>{{ $serviceRequest->city }}</td><td>{{ $serviceRequest->booking?->quoted_price !== null ? 'GH₵ ' . number_format((float) $serviceRequest->booking->quoted_price, 2) : 'Quote needed' }}</td><td><span class="pp-status pp-status-{{ str_replace('_', '-', $serviceRequest->status) }}">{{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}</span></td><td><a class="pp-view-button" href="{{ route('provider.bookings.show', $serviceRequest) }}">View request</a></td></tr>
        @empty
            <tr><td colspan="7"><div class="pp-empty"><strong>No matching requests</strong><p>Try another search or clear the filters. New customer requests appear here.</p><a href="{{ route('provider.bookings.index') }}">Show all requests</a></div></td></tr>
        @endforelse
    </tbody></table></div>
    <div class="pp-pagination">{{ $requests->links() }}</div>
</section>
@endsection
