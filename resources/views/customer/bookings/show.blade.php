@extends('layouts.customer-portal.customer-portal')
@section('title', 'Booking details')
@section('content')
<div class="cp-page-heading cp-booking-page-heading">
    <div>
        <span class="cp-eyebrow">BOOKING #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        <h1>{{ $booking->serviceRequest?->category?->name ?? 'Service booking' }}</h1>
        <p>Created {{ $booking->created_at->format('M j, Y') }}</p>
    </div>
    <span class="cp-status cp-status-{{ str_replace('_', '-', $booking->status) }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
</div>
<div class="cp-booking-detail-grid">
    <section class="cp-panel cp-booking-details">
        <div class="cp-section-title"><div><h2>{{ __('ui.appointment_details') }}</h2><p>Booking and provider information for this service.</p></div></div>
        <dl class="cp-detail-list">
            <div><dt>Service</dt><dd>{{ $booking->serviceRequest?->category?->name ?? ($booking->provider?->profession ?? 'Service request') }}</dd></div>
            <div><dt>Professional</dt><dd>{{ $booking->provider?->user?->name ?? 'Professional' }}</dd></div>
            <div><dt>Schedule</dt><dd>{{ $booking->scheduled_at?->format('l, M j, Y · g:i A') ?? 'Date and time to be arranged' }}</dd></div>
            <div><dt>Location</dt><dd>{{ $booking->serviceRequest?->city ?? $booking->provider?->city ?? 'Location to be confirmed' }}@if($booking->serviceRequest?->address), {{ $booking->serviceRequest->address }}@endif</dd></div>
            <div><dt>Agreed quote</dt><dd>{{ $booking->quoted_price !== null ? 'GH₵ ' . number_format((float) $booking->quoted_price, 2) : 'Not provided yet' }}</dd></div>
            @if($booking->final_price !== null)<div><dt>Final price</dt><dd>GH₵ {{ number_format((float) $booking->final_price, 2) }}</dd></div>@endif
        </dl>
        @if($booking->serviceRequest)
            <a class="cp-button" href="{{ route('customer.requests.show', $booking->serviceRequest) }}">Open service request</a>
        @else
            <div class="cp-flash">This booking is not connected to a service request. Contact support if you need help with it.</div>
        @endif
    </section>
    <aside class="cp-panel cp-booking-payment">
        <h2>{{ __('ui.payments') }}</h2>
        @error('payment')<div class="cp-flash cp-flash-error" role="alert">{{ $message }}</div>@enderror
        @php
            $paymentCompleted = $booking->serviceRequest?->status === 'completed';
            $paymentSettled = $booking->payments->contains(fn ($payment) => $payment->status === 'paid');
            $payableAmount = (float) ($booking->final_price ?? 0);
        @endphp
        @forelse($booking->payments as $payment)
            <div class="cp-payment-row">
                <span><strong>GH₵ {{ number_format((float) $payment->amount, 2) }}</strong><small>{{ $payment->created_at->format('M j, Y') }} @if($payment->paid_at) · Paid {{ $payment->paid_at->format('M j, Y') }}@endif</small></span>
                <span class="cp-status cp-status-{{ $payment->status }}">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
            </div>
        @empty
            <p class="cp-booking-muted">No payment has been recorded for this booking.</p>
        @endforelse
        @if($paymentSettled)
            <div class="cp-flash" role="status">Payment confirmed. Your receipt is available in payment history.</div>
        @elseif($paymentCompleted && $payableAmount > 0)
            @if(config('services.paystack.secret_key'))
                <p class="cp-booking-muted">Review the final amount before paying. Checkout is handled securely by Paystack.</p>
                <form method="POST" action="{{ route('customer.payments.initialize', $booking->serviceRequest) }}">
                    @csrf
                    <button class="cp-button" type="submit">Pay GH₵ {{ number_format($payableAmount, 2) }} securely</button>
                </form>
            @else
                <p class="cp-booking-muted">Online checkout is not set up yet. Contact LocalPro support to arrange payment.</p>
            @endif
        @elseif($paymentCompleted)
            <p class="cp-booking-muted">The professional has not added a final price yet.</p>
        @else
            <p class="cp-booking-muted">Payment becomes available after the job is marked complete and the final price is recorded.</p>
        @endif
        <a class="cp-text-action" href="{{ route('customer.payments.index') }}">View payment history →</a>
        @if($booking->serviceRequest)<a class="cp-text-action" href="{{ route('messages.show', $booking->serviceRequest) }}">Message professional →</a>@endif
    </aside>
</div>
@endsection
