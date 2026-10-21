@extends('layouts.customer-portal.customer-portal')
@section('title', 'Payments')
@section('content')
<div class="cp-page-heading"><div><span class="cp-eyebrow">{{ __('ui.payment_history') }}</span><h1>{{ __('ui.payments') }}</h1><p>Review payment records connected to your service requests.</p></div></div>
<div class="cp-flash" role="note">@if(config('services.paystack.secret_key'))When a job is complete and its final price is set, you can pay securely through Paystack. LocalPro only records a payment after the transaction is verified.@elseOnline checkout has not been configured yet. Contact LocalPro support to arrange payment.@endif</div>
@if(session('status'))<div class="cp-flash" role="status">{{ session('status') }}</div>@endif
@if($errors->has('payment'))<div class="cp-flash cp-flash-error" role="alert">{{ $errors->first('payment') }}</div>@endif
<section class="cp-panel cp-list-panel cp-payment-history">
    @forelse($payments as $payment)
        <article class="cp-record-row">
            <span class="cp-record-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M15.5 8.5c-.7-.7-1.7-1-3-1-1.5 0-2.5.8-2.5 1.9 0 2.9 5 1.2 5 4.1 0 1.2-1.1 2-2.8 2-1.2 0-2.3-.4-3.1-1.2M12 5.5v13"/></svg></span>
            <span class="cp-record-main"><strong>{{ $payment->booking->serviceRequest->category->name ?? 'Service request' }}</strong><small>{{ $payment->booking->provider->user->name ?? 'Professional' }} &middot; {{ $payment->created_at->format('M j, Y') }} @if($payment->paid_at)&middot; Paid {{ $payment->paid_at->format('M j, Y') }}@endif</small></span>
            <strong class="cp-payment-amount">GH&#8373; {{ number_format((float) $payment->amount, 2) }}</strong>
            <span class="cp-status cp-status-{{ $payment->status }}">{{ ucfirst(str_replace('_',' ', $payment->status)) }}</span>
            @if($payment->status !== 'paid' && $payment->booking?->serviceRequest?->status === 'completed')
                <a class="cp-text-action" href="{{ route('customer.requests.show', $payment->booking->serviceRequest) }}">Review job / retry payment →</a>
            @endif
        </article>
    @empty
        <div class="cp-empty"><span aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M15.5 8.5c-.7-.7-1.7-1-3-1-1.5 0-2.5.8-2.5 1.9 0 2.9 5 1.2 5 4.1 0 1.2-1.1 2-2.8 2-1.2 0-2.3-.4-3.1-1.2M12 5.5v13"/></svg></span><strong>No payment records yet</strong><p>Payment records appear here when a payment has been started for one of your jobs.</p><a href="{{ route('customer.requests.index') }}">View my requests &rarr;</a></div>
    @endforelse
</section>
<div class="cp-pagination">{{ $payments->links() }}</div>
@endsection
