@extends(auth()->user()->role === 'customer' ? 'layouts.customer-portal.customer-portal' : (auth()->user()->role === 'provider' ? 'layouts.provider-portal.provider-portal' : 'layouts.admin.admin'))
@section('title', 'Notifications')
@section('content')
<div class="cp-page-heading"><div><span class="cp-eyebrow">ACCOUNT UPDATES</span><h1>{{ __('ui.notifications') }}</h1><p>Updates about your requests and conversations.</p></div></div>
<section class="cp-panel cp-list-panel cp-notifications" aria-label="Recent notifications">
    @forelse($notifications as $notification)
        <article class="cp-record-row">
            <span class="cp-record-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg></span>
            <span class="cp-record-main"><strong>{{ $notification->title }}</strong><small>{{ $notification->body }}</small></span>
            <time class="cp-record-time" datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->diffForHumans() }}</time>
        </article>
    @empty
        <div class="cp-empty"><span aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg></span><strong>You are all caught up</strong><p>Notifications about your account and service requests will appear here.</p></div>
    @endforelse
</section>
<div class="cp-pagination">{{ $notifications->links() }}</div>
@endsection
