@extends('layouts.admin.admin')

@section('title', 'Bookings | LocalPro')

@section('content')
<div class="admin-page-content">
    <div class="requests-heading">
        <div><div class="eyebrow muted">MARKETPLACE ACTIVITY</div><h1>{{ __('ui.bookings') }}</h1><p>Review scheduled work, customer and provider details, and booking status.</p></div>
    </div>

    <form class="admin-search" method="GET" action="{{ route('admin.bookings.index') }}">
        <select name="status" aria-label="Filter bookings by status">
            <option value="">All statuses</option>
            @foreach(['pending', 'accepted', 'declined', 'completed', 'cancelled'] as $state)
                <option value="{{ $state }}" @selected(request('status') === $state)>{{ ucfirst($state) }}</option>
            @endforeach
        </select>
        <button class="button" type="submit">{{ __('ui.filter') }}</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Booking</th><th>{{ __('ui.customer') }}</th><th>{{ __('ui.provider') }}</th><th>Service</th><th>Schedule / location</th><th>{{ __('ui.price') }}</th><th>{{ __('ui.status') }}</th></tr></thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}<br><small>{{ $booking->created_at->format('M j, Y') }}</small></td>
                        <td>{{ $booking->customer->name ?? 'Customer account unavailable' }}</td>
                        <td>{{ $booking->provider->user->name ?? 'Provider unavailable' }}<br><small>{{ $booking->provider->profession ?? '' }}</small></td>
                        <td>{{ $booking->serviceRequest->category->name ?? $booking->provider->profession ?? 'Service booking' }}<br><small>{{ \Illuminate\Support\Str::limit($booking->serviceRequest->description ?? '', 90) }}</small></td>
                        <td>{{ $booking->scheduled_at?->format('M j, Y · g:i A') ?? 'To be arranged' }}<br><small>{{ $booking->serviceRequest->city ?? 'Location unavailable' }}@if($booking->serviceRequest?->address), {{ $booking->serviceRequest->address }}@endif</small></td>
                        <td>@if($booking->final_price !== null)GH₵ {{ number_format((float) $booking->final_price, 2) }}<br><small>Final</small>@elseif($booking->quoted_price !== null)GH₵ {{ number_format((float) $booking->quoted_price, 2) }}<br><small>Quote</small>@else Not set @endif</td>
                        <td><span class="status-pill status-{{ $booking->status }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="admin-empty-cell">No bookings match this filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $bookings->links() }}</div>
</div>
@endsection
