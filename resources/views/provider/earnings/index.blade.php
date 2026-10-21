@extends('layouts.provider-portal.provider-portal')
@section('title', 'Earnings and payouts')
@section('content')
<div class="pp-page-heading"><div><span class="pp-eyebrow">PAYMENT AND PAYOUT HISTORY</span><h1>{{ __('ui.earnings') }}</h1><p>See customer payments recorded against your jobs and request a manual payout from your available balance.</p></div></div>
@if($errors->any())<div class="pp-form-errors" role="alert"><strong>Action could not be completed.</strong><span>{{ $errors->first() }}</span></div>@endif
<section class="pp-earnings-summary"><article class="pp-panel"><small>PAYMENTS RECORDED FOR YOUR JOBS</small><strong>GH₵ {{ number_format((float) $paidTotal, 2) }}</strong></article><article class="pp-panel"><small>AVAILABLE TO REQUEST</small><strong>GH₵ {{ number_format($availablePayoutAmount, 2) }}</strong></article></section>
<section class="pp-panel pp-payout-info"><div class="pp-section-title"><div><h2>Manual provider payouts</h2><p>Requests are reviewed by the LocalPro admin team.</p></div></div><p>After approval, the transfer is made outside this website and the administrator records the transfer reference. Approval does not mean a transfer has already been sent. This version does not store bank or mobile-money details or send automatic transfers.</p>
    @if($profile->verification_status === 'approved' && $availablePayoutAmount > 0)
        <form method="POST" action="{{ route('provider.payouts.store') }}" class="pp-payout-request">@csrf<label for="payout-amount">Amount to request (GH₵)</label><input id="payout-amount" name="amount" type="number" min="0.01" max="{{ number_format($availablePayoutAmount, 2, '.', '') }}" step="0.01" required value="{{ old('amount') }}"><button class="pp-accept-button" type="submit">Request payout</button></form>
    @elseif($profile->verification_status !== 'approved')
        <p><strong>Your provider profile must be approved before you can request a payout.</strong> <a href="{{ route('provider.verification.index') }}">View verification</a></p>
    @else
        <p>No unallocated paid balance is available for a new payout request yet.</p>
    @endif
</section>
<section class="pp-panel pp-earnings-history"><div class="pp-section-title"><div><h2>Your payout requests</h2><p>Requests remain listed after approval, rejection, or payment.</p></div></div>
    @forelse($payoutRequests as $payout)<article class="pp-managed-service"><div class="pp-managed-service-copy"><strong>GH₵ {{ number_format((float) $payout->amount, 2) }}</strong><span>Requested {{ $payout->created_at->format('M j, Y') }}@if($payout->transfer_reference) · Reference {{ $payout->transfer_reference }}@endif</span>@if($payout->admin_note)<p>Admin note: {{ $payout->admin_note }}</p>@endif</div><span class="pp-status pp-status-{{ $payout->status }}">{{ ucfirst($payout->status) }}</span></article>@empty<div class="pp-empty"><strong>No payout requests yet</strong><p>Eligible providers can request available paid earnings above.</p></div>@endforelse
    <div class="pp-pagination">{{ $payoutRequests->links() }}</div>
</section>
<section class="pp-panel pp-earnings-history"><div class="pp-section-title"><div><h2>Customer payment records</h2><p>These are payments recorded against your jobs, not proof that a provider transfer was made.</p></div></div>
    @forelse($payments as $payment)<article class="pp-managed-service"><div class="pp-managed-service-copy"><strong>{{ $payment->booking?->serviceRequest?->category?->name ?? 'Service request' }} · GH₵ {{ number_format((float) $payment->amount, 2) }}</strong><span>{{ $payment->customer?->name ?? 'Customer' }} · {{ $payment->created_at->format('M j, Y') }}</span></div><span class="pp-status pp-status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></article>@empty<div class="pp-empty"><strong>No customer payments yet</strong><p>Payment records linked to your jobs will appear here.</p></div>@endforelse
    <div class="pp-pagination">{{ $payments->links() }}</div>
</section>
@endsection
