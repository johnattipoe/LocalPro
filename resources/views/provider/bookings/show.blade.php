@extends('layouts.provider-portal.provider-portal')
@section('title', 'Request details')
@section('content')
<a class="pp-back-link" href="{{ route('provider.bookings.index') }}">&larr; All requests</a>
@if($errors->any())<div class="pp-form-errors" role="alert"><strong>Action could not be completed.</strong><span>{{ $errors->first() }}</span></div>@endif
<div class="pp-page-heading pp-request-page-heading"><div><span class="pp-eyebrow">CUSTOMER REQUEST #{{ str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT) }}</span><h1>{{ $serviceRequest->category?->name ?? (auth()->user()->providerProfile?->profession ?? 'Service request') }}</h1><p>Received {{ $serviceRequest->created_at->format('F j, Y') }}</p></div><span class="pp-status pp-status-{{ str_replace('_', '-', $serviceRequest->status) }}">{{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}</span></div>
<div class="pp-request-detail-grid">
    <section class="pp-panel pp-request-description"><h2>What the customer needs</h2><p>{{ $serviceRequest->description }}</p><dl><div><dt>Location</dt><dd>{{ $serviceRequest->city }}@if($serviceRequest->address), {{ $serviceRequest->address }}@endif</dd></div><div><dt>Preferred date</dt><dd>{{ $serviceRequest->preferred_at?->format('M j, Y g:i A') ?? 'Flexible' }}</dd></div><div><dt>Appointment</dt><dd>{{ $serviceRequest->booking?->scheduled_at?->format('M j, Y g:i A') ?? 'To be arranged' }}</dd></div><div><dt>Quote</dt><dd>{{ $serviceRequest->booking?->quoted_price !== null ? 'GH₵ ' . number_format((float) $serviceRequest->booking->quoted_price, 2) : 'Not set yet' }}</dd></div></dl>
        @if(in_array($serviceRequest->status, ['pending', 'accepted'], true))<div class="request-next-step"><span>i</span><p><strong>Keep the customer informed.</strong><br>Discuss the scope and agree on a quote and schedule before starting work.</p></div>@endif
    </section>
    <aside class="pp-panel pp-request-customer"><h2>{{ __('ui.customer') }}</h2><div class="pp-request-customer-avatar">{{ mb_substr($serviceRequest->customer?->name ?? 'C', 0, 1) }}</div><strong>{{ $serviceRequest->customer?->name ?? 'Customer' }}</strong><p>{{ $serviceRequest->customer?->phone ?? 'Phone number not provided' }}</p><p>{{ $serviceRequest->customer?->email }}</p><a class="pp-accept-button" href="{{ route('messages.show', $serviceRequest) }}">Message customer</a>
        @if($serviceRequest->status === 'pending')
            @if(auth()->user()->providerProfile?->verification_status === 'approved')
                <form class="pp-request-action-form" method="POST" action="{{ route('provider.bookings.update', $serviceRequest) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="accepted"><label>Quote (GH₵, optional)<input type="number" name="quoted_price" min="0" step="0.01" value="{{ old('quoted_price') }}"></label><label>Proposed date<input type="date" name="scheduled_at" min="{{ now()->toDateString() }}" value="{{ old('scheduled_at') }}"></label><button class="pp-accept-button" type="submit">Accept request</button></form>
            @endif
            <form class="pp-request-action-form" method="POST" action="{{ route('provider.bookings.update', $serviceRequest) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="declined"><button class="pp-decline-button" type="submit">Decline request</button></form>
        @elseif($serviceRequest->status === 'accepted')
            <form class="pp-request-action-form" method="POST" action="{{ route('provider.bookings.update', $serviceRequest) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="accepted"><label>Update quote (GH₵)<input type="number" name="quoted_price" min="0" step="0.01" value="{{ old('quoted_price', $serviceRequest->booking?->quoted_price) }}"></label><label>Update appointment date<input type="date" name="scheduled_at" min="{{ now()->toDateString() }}" value="{{ old('scheduled_at', $serviceRequest->booking?->scheduled_at?->format('Y-m-d')) }}"></label><button class="pp-secondary-action" type="submit">Save quote or schedule</button></form>
            <form class="pp-request-action-form" method="POST" action="{{ route('provider.bookings.update', $serviceRequest) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="completed"><label>Final price (GH₵, optional)<input type="number" name="final_price" min="0" step="0.01" value="{{ old('final_price', $serviceRequest->booking?->quoted_price) }}"></label><button class="pp-accept-button" type="submit">Mark completed</button></form>
        @endif
    </aside>
</div>
@endsection
