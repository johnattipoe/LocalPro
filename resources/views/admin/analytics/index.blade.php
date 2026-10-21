@extends('layouts.admin.admin')

@section('title', 'Marketplace reports | LocalPro')

@section('content')
<section class="admin-content admin-analytics-page">
    <div class="admin-analytics-heading">
        <div><div class="eyebrow muted">MARKETPLACE PERFORMANCE</div><h1>Reports & analytics</h1><p class="request-intro">Live marketplace totals for the last 30 days, with full booking and service category breakdowns.</p></div>
        <a class="button button-secondary" href="{{ route('admin.dashboard') }}">Back to dashboard</a>
    </div>

    <div class="admin-analytics-metrics">
        <article><span>▣</span><div><small>{{ __('ui.bookings') }}</small><strong>{{ number_format($stats['bookings']) }}</strong><em>Last 30 days</em></div></article>
        <article><span>♙</span><div><small>New customers</small><strong>{{ number_format($stats['customers']) }}</strong><em>Last 30 days</em></div></article>
        <article><span>⌕</span><div><small>Service requests</small><strong>{{ number_format($stats['requests']) }}</strong><em>Last 30 days</em></div></article>
        <article><span>₵</span><div><small>Payments received</small><strong>GH₵ {{ number_format($stats['revenue'], 2) }}</strong><em>Paid transactions, last 30 days</em></div></article>
    </div>

    <div class="admin-analytics-panels">
        <section class="admin-panel analytics-trend-panel">
            <div class="admin-panel-heading"><div><h2>Booking activity</h2><p>Daily bookings over the last 30 days</p></div><a href="{{ route('admin.bookings.index') }}">Open bookings <span>→</span></a></div>
            <div class="analytics-bar-chart" role="img" aria-label="Daily booking activity for the last 30 days">
                @php($maxTrend = max(1, (int) $bookingTrend->max('count')))
                @foreach($bookingTrend as $day)
                    <div class="analytics-bar-column" title="{{ $day['label'] }}: {{ $day['count'] }} bookings"><span>{{ $day['count'] ?: '' }}</span><i style="--bar-height:{{ max(3, round($day['count'] * 100 / $maxTrend)) }}%"></i><small>{{ $loop->iteration === 1 || $loop->iteration % 5 === 0 || $loop->last ? $day['label'] : '' }}</small></div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel analytics-status-panel">
            <div class="admin-panel-heading"><div><h2>Booking statuses</h2><p>All recorded bookings</p></div></div>
            <div class="analytics-status-list">
                @forelse($bookingStatuses as $item)
                    <div><span class="admin-status-pill status-{{ $item->status }}">{{ str_replace('_', ' ', ucfirst($item->status)) }}</span><strong>{{ number_format($item->total) }}</strong><i><b style="width:{{ max(3, round($item->total * 100 / max(1, $bookingStatuses->sum('total')))) }}%"></b></i></div>
                @empty
                    <p class="admin-chart-empty">Booking statuses will appear after customers create bookings.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="admin-panel analytics-category-panel">
        <div class="admin-panel-heading"><div><h2>Popular service categories</h2><p>Ranked by customer service requests</p></div><a href="{{ route('admin.categories.index') }}">Manage categories <span>→</span></a></div>
        <div class="admin-table-scroll"><table class="admin-dashboard-table"><thead><tr><th>Rank</th><th>Service category</th><th>Customer requests</th><th>Share</th></tr></thead><tbody>
            @forelse($topCategories as $category)
                <tr><td>{{ $loop->iteration }}</td><td><strong>{{ $category->category->name ?? 'Uncategorized' }}</strong></td><td>{{ number_format($category->total) }}</td><td>{{ $categoryTotal ? number_format($category->total * 100 / $categoryTotal, 1) : '0.0' }}%</td></tr>
            @empty
                <tr><td colspan="4" class="admin-empty-cell">Category results will appear when customers submit service requests.</td></tr>
            @endforelse
        </tbody></table></div>
    </section>
    <p class="analytics-date-note">Report generated {{ now()->format('M j, Y \a\t g:i A') }}. Counts reflect records currently stored in LocalPro.</p>
</section>
@endsection
