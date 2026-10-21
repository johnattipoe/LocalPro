@extends('layouts.admin.admin')

@section('title', __('ui.administrator_dashboard').' | LocalPro')

@section('content')
<div class="admin-dashboard">
    <div class="admin-dashboard-heading">
        <div>
            <span class="admin-eyebrow">WELCOME BACK,</span>
            <h1>{{ __('ui.administrator') }}</h1>
            <p>{{ __('ui.here_is_platform_today') }}</p>
        </div>
        <div class="admin-date"><i data-lucide="calendar-days" aria-hidden="true"></i><div><strong>{{ now()->format('D, M j, Y') }}</strong><small>{{ now()->format('g:i A') }}</small></div></div>
    </div>

    <div class="admin-dashboard-grid">
        <div class="admin-primary-column">
            <section class="admin-metric-grid" aria-label="Marketplace summary">
                <article class="admin-metric-card metric-users"><span class="metric-icon"><i data-lucide="users-round" aria-hidden="true"></i></span><div><small>{{ __('ui.total_users') }}</small><strong>{{ number_format($stats['users']) }}</strong><span class="metric-trend">{{ $stats['users_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['users_change']) }}%</span><em>vs. last 30 days</em></div></article>
                <article class="admin-metric-card metric-providers"><span class="metric-icon"><i data-lucide="shield-check" aria-hidden="true"></i></span><div><small>{{ __('ui.verified_providers') }}</small><strong>{{ number_format($stats['verified']) }}</strong><span class="metric-trend">{{ $stats['providers_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['providers_change']) }}%</span><em>{{ __('ui.vs_last_30_days') }}</em></div></article>
                <article class="admin-metric-card metric-bookings"><span class="metric-icon"><i data-lucide="calendar-days" aria-hidden="true"></i></span><div><small>{{ __('ui.bookings') }}</small><strong>{{ number_format($stats['bookings']) }}</strong><span class="metric-trend">{{ $stats['bookings_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['bookings_change']) }}%</span><em>{{ __('ui.vs_last_30_days') }}</em></div></article>
                <article class="admin-metric-card metric-revenue"><span class="metric-icon"><i data-lucide="circle-dollar-sign" aria-hidden="true"></i></span><div><small>{{ __('ui.platform_revenue') }}</small><strong>GH₵ {{ number_format($stats['revenue'], 2) }}</strong><span class="metric-trend">{{ $stats['revenue_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['revenue_change']) }}%</span><em>vs. last 30 days</em></div></article>
            </section>

            <div class="admin-chart-grid">
            <section class="admin-panel bookings-chart-panel">
                    <div class="admin-panel-heading"><div><h2>{{ __('ui.bookings_overview') }}</h2><p>Bookings created over the last 7 days</p></div><a href="{{ route('admin.bookings.index') }}">View bookings <span>→</span></a></div>
                    <div class="admin-line-chart" role="img" aria-label="Bookings over the last seven days">
                        <div class="chart-y-labels"><span>{{ $chartMax }}</span><span>{{ (int) round($chartMax * .75) }}</span><span>{{ (int) round($chartMax * .5) }}</span><span>{{ (int) round($chartMax * .25) }}</span><span>0</span></div>
                        <svg viewBox="0 0 690 190" preserveAspectRatio="none" aria-hidden="true">
                            <defs><linearGradient id="bookingArea" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#0e9c83" stop-opacity=".23"/><stop offset="1" stop-color="#0e9c83" stop-opacity=".015"/></linearGradient></defs>
                            @foreach([18,57,96,135,174] as $gridY)<line x1="30" y1="{{ $gridY }}" x2="660" y2="{{ $gridY }}" stroke="#e9eef2" stroke-dasharray="3 5"/>@endforeach
                            <polygon points="{{ $chartPoints->first()['x'] }},174 {{ $chartPoints->map(fn($point) => $point['x'].','.$point['y'])->implode(' ') }} {{ $chartPoints->last()['x'] }},174" fill="url(#bookingArea)"/>
                            <polyline points="{{ $chartPoints->map(fn($point) => $point['x'].','.$point['y'])->implode(' ') }}" fill="none" stroke="#07977c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            @foreach($chartPoints as $point)<circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4.5" fill="#07977c" stroke="#fff" stroke-width="2"><title>{{ $point['label'] }}: {{ $point['value'] }} bookings</title></circle>@endforeach
                        </svg>
                        <div class="chart-x-labels">@foreach($chartPoints as $point)<span>{{ $point['label'] }}</span>@endforeach</div>
                    </div>
                </section>

                <section class="admin-panel category-chart-panel">
                    <div class="admin-panel-heading"><div><h2>{{ __('ui.service_categories') }}</h2><p>Distribution of customer requests</p></div><a href="{{ route('admin.categories.index') }}">Manage <span>→</span></a></div>
                    @if($categories->isNotEmpty())
                        <div class="category-chart-body"><div class="category-donut" style="background:{{ $donutBackground }}"><div><strong>{{ number_format($categoryTotal) }}</strong><span>Requests</span></div></div><ul class="category-legend">@foreach($categories as $category)<li><i style="--category-color:{{ $category['color'] }}"></i><span>{{ $category['name'] }}</span><b>{{ $category['percent'] }}%</b></li>@endforeach</ul></div>
                    @else
                        <div class="admin-chart-empty"><i data-lucide="chart-no-axes-combined" aria-hidden="true"></i><strong>No service requests yet</strong><p>Category activity appears as customers make requests.</p></div>
                    @endif
                </section>
            </div>

            <div class="admin-table-grid">
                <section class="admin-panel admin-table-panel">
                    <div class="admin-panel-heading"><div><h2>{{ __('ui.pending_verifications') }}</h2><p>{{ number_format($stats['pending_verifications']) }} profiles waiting for review</p></div><a href="{{ route('admin.providers.index', ['status' => 'pending']) }}">View all <span>→</span></a></div>
                    <div class="admin-table-scroll"><table class="admin-dashboard-table"><thead><tr><th>{{ __('ui.provider') }}</th><th>Service</th><th>Documents</th><th>{{ __('ui.status') }}</th><th>{{ __('ui.action') }}</th></tr></thead><tbody>
                        @forelse($pendingProviders as $provider)
                            <tr><td>@php($adminProviderPhoto = $provider->profile_photo_path ? \Illuminate\Support\Facades\Storage::disk($provider->profile_photo_disk ?: 'public')->url($provider->profile_photo_path) : null)<span class="admin-table-person">@if($adminProviderPhoto)<i class="admin-table-photo"><img src="{{ $adminProviderPhoto }}" alt="Profile photo of {{ $provider->user->name }}"></i>@else<i>{{ mb_strtoupper(mb_substr($provider->user->name, 0, 1)) }}</i>@endif<span><b>{{ $provider->user->name }}</b><small>{{ (int) $provider->review_count > 0 ? number_format((float) $provider->average_rating, 1).' stars - '.$provider->review_count.' reviews' : 'New provider - No reviews yet' }}</small></span></span></td><td>{{ $provider->profession }}</td><td><span class="admin-document-links">@forelse($provider->verifications as $document)<a href="{{ route('admin.verification.download', $document) }}" aria-label="Download {{ ucwords(str_replace('_', ' ', $document->document_type)) }}" title="Download {{ ucwords(str_replace('_', ' ', $document->document_type)) }}">Document</a>@empty<span>None</span>@endforelse</span></td><td><span class="admin-status-pill status-pending">{{ __('ui.pending') }}</span></td><td><a class="admin-review-button" href="{{ route('admin.providers.index', ['status' => 'pending']) }}">Review</a></td></tr>
                        @empty
                            <tr><td colspan="5" class="admin-empty-cell">No provider verifications are waiting for review.</td></tr>
                        @endforelse
                    </tbody></table></div>
                </section>

                <section class="admin-panel admin-table-panel">
                    <div class="admin-panel-heading"><div><h2>{{ __('ui.recent_bookings') }}</h2><p>Latest customer and provider activity</p></div><a href="{{ route('admin.bookings.index') }}">View all <span>→</span></a></div>
                    <div class="admin-table-scroll"><table class="admin-dashboard-table"><thead><tr><th>{{ __('ui.customer') }}</th><th>Service</th><th>{{ __('ui.date') }}</th><th>{{ __('ui.status') }}</th></tr></thead><tbody>
                        @forelse($recentBookings as $booking)
                            <tr><td>{{ $booking->customer->name ?? 'Customer' }}</td><td>{{ $booking->serviceRequest->category->name ?? $booking->serviceRequest->description ?? 'Local service' }}</td><td>{{ optional($booking->scheduled_at ?? $booking->created_at)->format('M j, Y') }}</td><td><span class="admin-status-pill status-{{ $booking->status }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span></td></tr>
                        @empty
                            <tr><td colspan="4" class="admin-empty-cell">Bookings will appear here when a customer schedules a service.</td></tr>
                        @endforelse
                    </tbody></table></div>
                </section>
            </div>

            <div class="admin-dashboard-note"><i data-lucide="shield-check" aria-hidden="true"></i><div><strong>LocalPro Admin Dashboard</strong><p>Monitor marketplace activity, manage users and providers, and keep local services safe and reliable.</p></div></div>
        </div>

        <aside class="admin-right-column">
            <section class="admin-panel activity-panel"><div class="admin-panel-heading"><div><h2>Recent Activity</h2></div><a href="{{ route('notifications.index') }}">View all <span>→</span></a></div>
                <div class="activity-list">@forelse($activities as $activity)<a class="activity-item" href="{{ $activity['url'] }}"><span class="activity-icon activity-{{ $activity['kind'] }}"><i data-lucide="{{ $activity['icon'] }}" aria-hidden="true"></i></span><span class="activity-copy"><strong>{{ $activity['title'] }}</strong><small>{{ $activity['detail'] }}</small></span><time>{{ $activity['at']->diffForHumans(null, true) }}</time></a>@empty<div class="activity-empty">Marketplace updates will appear here as people join and book services.</div>@endforelse</div>
            </section>

            <section class="admin-panel quick-actions-panel"><div class="admin-panel-heading"><div><h2>Quick Actions</h2></div></div>
                <a class="admin-quick-action" href="{{ route('admin.providers.index', ['status' => 'pending']) }}"><span class="quick-action-icon quick-green"><i data-lucide="shield-check" aria-hidden="true"></i></span><b>Review Pending Providers</b><span class="quick-arrow">›</span></a>
                <a class="admin-quick-action" href="{{ route('admin.categories.index') }}"><span class="quick-action-icon quick-teal"><i data-lucide="tag" aria-hidden="true"></i></span><b>Manage Service Categories</b><span class="quick-arrow">›</span></a>
                <a class="admin-quick-action" href="{{ route('admin.bookings.index') }}"><span class="quick-action-icon quick-blue"><i data-lucide="clipboard-list" aria-hidden="true"></i></span><b>View All Bookings</b><span class="quick-arrow">›</span></a>
                <a class="admin-quick-action" href="{{ route('admin.analytics.index') }}"><span class="quick-action-icon quick-gray"><i data-lucide="chart-no-axes-combined" aria-hidden="true"></i></span><b>Generate Report</b><span class="quick-arrow">›</span></a>
            </section>

            <section class="admin-panel service-area-panel"><div class="admin-panel-heading"><div><h2>{{ __('ui.service_areas') }}</h2></div><span class="area-map-label">Ho, Volta Region</span></div><div class="area-map-art" role="img" aria-label="Illustration of Ho and its surrounding service region"><span class="area-mountain area-mountain-one"></span><span class="area-mountain area-mountain-two"></span><i class="area-map-pin">●</i></div><div class="area-info"><div><strong>Ho</strong><small>⌖ &nbsp;Volta Region, Ghana</small></div><span>● Active</span></div></section>
        </aside>
    </div>
</div>
@endsection
