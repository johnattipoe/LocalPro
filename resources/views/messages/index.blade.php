@extends(auth()->user()->role === 'customer' ? 'layouts.customer-portal.customer-portal' : (auth()->user()->role === 'provider' ? 'layouts.provider-portal.provider-portal' : 'layouts.admin.admin'))
@section('title', 'Conversation')
@section('content')
<div class="cp-page-heading cp-message-page-heading">
    <div><span class="cp-eyebrow">REQUEST #{{ str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT) }}</span><h1>Conversation with {{ $otherUser->name }}</h1><p>{{ $serviceRequest->category->name ?? $serviceRequest->provider->profession }} &middot; {{ $serviceRequest->city }}</p></div>
    <div class="cp-message-heading-actions"><span class="cp-status cp-status-{{ str_replace('_', '-', $serviceRequest->status) }}">{{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}</span><a class="cp-button" href="{{ auth()->user()->role === 'provider' ? route('provider.bookings.show', $serviceRequest) : route('customer.requests.show', $serviceRequest) }}">Back to request</a></div>
</div>
<section class="cp-message-legacy">
    @if(session('status'))<div class="flash-note">&#10003; {{ session('status') }}</div>@endif
    <section class="message-panel">
        <div class="message-history" data-message-history data-poll-url="{{ route('messages.updates', $serviceRequest) }}" data-current-user="{{ auth()->id() }}" aria-live="polite" aria-relevant="additions text">
            @forelse($messages as $message)
                <article class="message-bubble {{ $message->sender_id === auth()->id() ? 'message-mine' : 'message-theirs' }}" data-message-id="{{ $message->id }}" data-sender-id="{{ $message->sender_id }}">
                    <small>{{ $message->sender_id === auth()->id() ? 'You' : $otherUser->name }} &middot; {{ $message->created_at->format('M j, g:i A') }}</small><p>{{ $message->body }}</p>
                </article>
            @empty
                <div class="message-empty" data-message-empty><strong>Start the conversation</strong><p>Discuss the job details, quote, and timing here.</p></div>
            @endforelse
        </div>
        <p class="message-connection" data-message-connection role="status">Messages refresh while this page is open.</p>
        <form class="message-compose" method="POST" action="{{ route('messages.store', $serviceRequest) }}">@csrf
            <label for="message-body">Message {{ $otherUser->name }}</label>
            <textarea id="message-body" name="body" rows="3" maxlength="4000" required placeholder="Write a message about this request...">{{ old('body') }}</textarea>
            @error('body')<small class="field-error">{{ $message }}</small>@enderror
            <button class="button" type="submit">Send message <span>&#8594;</span></button>
        </form>
    </section>
</section>
@endsection
