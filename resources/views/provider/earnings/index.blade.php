<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Earnings and payout requests | LocalPro</title>
    @vite(['resources/css/app.css','resources/ts/app.ts'])
</head>
<body>
<header class="topbar">
    <a class="brand" href="{{ route('provider.dashboard') }}"><span class="brand-mark">&#10003;</span><span>local<span>pro</span><small>PROVIDER PORTAL</small></span></a>
    <nav class="main-nav"><a href="{{ route('provider.dashboard') }}">Dashboard</a><a href="{{ route('provider.bookings.index') }}">Requests</a><a class="active" href="{{ route('provider.earnings.index') }}">Earnings</a></nav>
</header>
<main class="requests-page">
    <div class="requests-heading"><div><div class="eyebrow muted">PAYMENT AND PAYOUT HISTORY</div><h1>Earnings</h1><p>See customer payments recorded against your completed jobs and track payout requests.</p></div></div>
    @if(session('status'))<div class="flash-note" role="status">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="flash-note" role="alert">{{ $errors->first() }}</div>@endif

    <section class="admin-stats provider-stats">
        <article><small>PAYMENTS RECORDED FOR YOUR JOBS</small><strong>GHS {{ number_format((float) $paidTotal, 2) }}</strong></article>
        <article><small>AVAILABLE TO REQUEST</small><strong>GHS {{ number_format($availablePayoutAmount, 2) }}</strong></article>
    </section>

    <section class="request-detail-card payout-explainer">
        <div class="eyebrow muted">HOW MANUAL PAYOUT REQUESTS WORK</div>
        <p>Request an amount from your available balance. A LocalPro administrator will review the request. If it is approved, the transfer must be made outside this website; the administrator then records the transfer reference here. A request or approval does not mean money has already been sent.</p>
        <p>This version does not collect bank or mobile-money account details, automatically transfer money, or calculate a marketplace commission. Contact support if you need to update your payment arrangements.</p>
        @if($profile->verification_status === 'approved' && $availablePayoutAmount > 0)
            <form method="POST" action="{{ route('provider.payouts.store') }}" class="payout-request-form">
                @csrf
                <label for="payout-amount">Amount to request (GHS)</label>
                <input id="payout-amount" name="amount" type="number" min="0.01" max="{{ number_format($availablePayoutAmount, 2, '.', '') }}" step="0.01" required value="{{ old('amount') }}">
                <button class="button" type="submit">Request a manual payout</button>
            </form>
        @elseif($profile->verification_status !== 'approved')
            <p><strong>Your provider profile must be approved before you can request a payout.</strong></p>
        @else
            <p>There is no unallocated paid balance available for a new request yet.</p>
        @endif
    </section>

    <section class="request-detail-card">
        <div class="admin-section-heading"><div><h2>Your payout requests</h2><p>Requests stay in this history after approval, rejection, or payment.</p></div></div>
        @forelse($payoutRequests as $payout)
            <article class="admin-request-row payout-history-row">
                <div><strong>GHS {{ number_format((float) $payout->amount, 2) }}</strong><small>Requested {{ $payout->created_at->format('M j, Y') }}@if($payout->transfer_reference) - Reference {{ $payout->transfer_reference }}@endif</small>@if($payout->admin_note)<small>Admin note: {{ $payout->admin_note }}</small>@endif</div>
                <span class="status-pill status-{{ $payout->status }}">{{ ucfirst($payout->status) }}</span>
            </article>
        @empty
            <p class="admin-empty">You have not requested a payout yet.</p>
        @endforelse
        {{ $payoutRequests->links() }}
    </section>

    <section class="request-detail-card">
        <div class="admin-section-heading"><div><h2>Customer payment records</h2><p>These are payments recorded by LocalPro against your jobs, not proof of provider transfer.</p></div></div>
        @forelse($payments as $payment)
            <article class="admin-request-row">
                <div><strong>{{ $payment->booking->serviceRequest->category->name ?? 'Service request' }} - GHS {{ number_format((float) $payment->amount, 2) }}</strong><small>{{ $payment->customer->name }} - {{ $payment->created_at->format('M j, Y') }}</small></div>
                <span class="status-pill status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
            </article>
        @empty
            <p class="admin-empty">No customer payment records are linked to your jobs yet.</p>
        @endforelse
        {{ $payments->links() }}
    </section>
</main>
</body>
</html>
