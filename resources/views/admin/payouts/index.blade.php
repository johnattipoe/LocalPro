@extends('layouts.admin.admin')

@section('title', 'Provider payout requests')

@section('content')
<section class="admin-content">
    <div class="admin-page-heading"><div><div class="eyebrow muted">PROVIDER SETTLEMENTS</div><h1>Provider payout requests</h1><p class="request-intro">Review requests against the paid customer payment ledger. Transfers happen outside LocalPro. Verify the provider's payment instructions through your approved process before sending funds, then record the external transfer reference.</p></div></div>
    @if(session('status'))<div class="flash-note" role="status">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="flash-note" role="alert">{{ $errors->first() }}</div>@endif

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Requested</th><th>{{ __('ui.provider') }}</th><th>Amount</th><th>{{ __('ui.status') }}</th><th>Transfer reference</th><th>Review</th></tr></thead>
            <tbody>
                @forelse($payouts as $payout)
                    <tr>
                        <td>{{ $payout->created_at->format('M j, Y') }}<br><small>Requested by {{ $payout->requester->name }}</small></td>
                        <td><strong>{{ $payout->provider->user->name }}</strong><br><small>{{ $payout->provider->profession }} - {{ $payout->provider->city }}</small></td>
                        <td>GHS {{ number_format((float) $payout->amount, 2) }}</td>
                        <td><span class="status-pill status-{{ $payout->status }}">{{ ucfirst($payout->status) }}</span>@if($payout->admin_note)<br><small>{{ $payout->admin_note }}</small>@endif</td>
                        <td>{{ $payout->transfer_reference ?: 'Not recorded' }}</td>
                        <td>
                            @if(in_array($payout->status, ['requested', 'approved'], true))
                                <form method="POST" action="{{ route('admin.payouts.update', $payout) }}" class="support-update-form">
                                    @csrf @method('PATCH')
                                    <label>Next status<select name="status" required>
                                        @if($payout->status === 'requested')<option value="approved">Approve request</option><option value="rejected">Reject request</option>@endif
                                        @if($payout->status === 'approved')<option value="paid">Record as paid</option><option value="rejected">Reject request</option>@endif
                                    </select></label>
                                    <label>External transfer reference<input name="transfer_reference" maxlength="120" value="{{ old('transfer_reference', $payout->transfer_reference) }}" placeholder="Required when marking paid"></label>
                                    <label>Note for provider<textarea name="admin_note" rows="2" maxlength="2000" placeholder="Explain approval or rejection">{{ old('admin_note', $payout->admin_note) }}</textarea></label>
                                    <button class="button button-small" type="submit">Save payout status</button>
                                </form>
                            @else
                                <small>This payout request is closed.</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No payout requests have been submitted.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $payouts->links() }}
</section>
@endsection
