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
            @if($booking->isRecurring())
                <div><dt>Recurrence</dt><dd>{{ ucfirst($booking->recurrence_pattern) }} · {{ $booking->occurrence_count }} remaining{{ $booking->next_occurrence ? ' · Next: ' . $booking->next_occurrence->format('M j, Y') : '' }}</dd></div>
            @endif
        </dl>
        @if($booking->serviceRequest)
            <a class="cp-button" href="{{ route('customer.requests.show', $booking->serviceRequest) }}">Open service request</a>
        @else
            <div class="cp-flash">This booking is not connected to a service request. Contact support if you need help with it.</div>
        @endif

        @if(!$booking->calendar_synced)
            <form method="POST" action="{{ route('bookings.calendar.sync', $booking) }}" style="display:inline">@csrf
                <button class="cp-button cp-button-outline" type="button" onclick="this.closest('form').requestSubmit()">Sync to Google Calendar</button>
            </form>
        @else
            <form method="POST" action="{{ route('bookings.calendar.unsync', $booking) }}" style="display:inline">@csrf
                <button class="cp-button cp-button-outline" type="button" onclick="this.closest('form').requestSubmit()">Remove from Calendar</button>
            </form>
        @endif

        @if($booking->isRecurring() && $booking->occurrences->count() > 0)
            <div class="cp-section-title"><div><h2>Upcoming occurrences</h2><p>Future bookings in this recurring series.</p></div></div>
            <ul class="cp-record-list">
                @foreach($booking->occurrences as $occurrence)
                    <li class="cp-record-row"><span class="cp-record-icon">○</span><span class="cp-record-main"><strong>{{ $occurrence->scheduled_at?->format('M j, Y · g:i A') }}</strong><small>{{ ucfirst(str_replace('_', ' ', $occurrence->status)) }}</small></span><span class="cp-status cp-status-{{ str_replace('_','-', $occurrence->status) }}">{{ ucfirst(str_replace('_', ' ', $occurrence->status)) }}</span></li>
                @endforeach
            </ul>
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

        @if($booking->isRecurring() && $booking->recurrence_pattern !== 'none')
            <div class="cp-section-title"><div><h2>Change recurrence</h2><p>Update how often this booking repeats.</p></div></div>
            <form method="POST" action="{{ route('bookings.recurrence', $booking) }}">@csrf
                <label><span>Recurrence pattern</span>
                    <select name="recurrence_pattern">
                        <option value="none" @selected($booking->recurrence_pattern === 'none')>None (one-time)</option>
                        <option value="weekly" @selected($booking->recurrence_pattern === 'weekly')>Weekly</option>
                        <option value="monthly" @selected($booking->recurrence_pattern === 'monthly')>Monthly</option>
                    </select>
                </label>
                <label><span>Total occurrences</span>
                    <input type="number" name="occurrence_count" min="1" max="52" value="{{ $booking->recurrence_pattern !== 'none' ? $booking->occurrence_count : 4 }}">
                </label>
                <button class="cp-button" type="submit">Update schedule</button>
            </form>
        @elseif(!$booking->isRecurring())
            <div class="cp-section-title"><div><h2>Set up recurring</h2><p>Make this booking repeat weekly or monthly.</p></div></div>
            <form method="POST" action="{{ route('bookings.recurrence', $booking) }}">@csrf
                <label><span>Recurrence pattern</span>
                    <select name="recurrence_pattern">
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </label>
                <label><span>Total occurrences</span>
                    <input type="number" name="occurrence_count" min="1" max="52" value="4">
                </label>
                <button class="cp-button" type="submit">Set recurring schedule</button>
            </form>
        @endif
    </aside>
</div>
@endsection