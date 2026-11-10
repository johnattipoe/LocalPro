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
        <div class="message-history" data-message-history data-pusher-key="{{ config('broadcasting.connections.pusher.key') }}" data-pusher-cluster="{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}" data-service-request-id="{{ $serviceRequest->id }}" data-poll-url="{{ route('messages.updates', $serviceRequest) }}" data-current-user="{{ auth()->id() }}" aria-live="polite" aria-relevant="additions text">
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
            <div class="message-compose-row">
                <textarea id="message-body" name="body" rows="3" maxlength="4000" required placeholder="Write a message about this request...">{{ old('body') }}</textarea>
                <button type="button" class="button button-small quick-replies-toggle" data-quick-replies-toggle><i data-lucide="message-circle" aria-hidden="true"></i> Quick replies</button>
                <button type="button" class="button button-small start-call-button" data-start-call><i data-lucide="phone" aria-hidden="true"></i> Call</button>
            </div>
            @error('body')<small class="field-error">{{ $message }}</small>@enderror
            <button class="button" type="submit">Send message <span>&#8594;</span></button>
        </form>

        <dialog class="quick-replies-dialog" data-quick-replies-dialog>
            <div class="quick-replies-dialog-header">
                <h3>Quick replies</h3>
                <button class="quick-replies-dialog-close" data-quick-replies-close><i data-lucide="x" aria-hidden="true"></i></button>
            </div>
            <div class="quick-replies-dialog-list" data-quick-replies-list>
                @php($systemTemplates = \App\Models\QuickReplyTemplate::systemTemplates()->orderBy('title')->get())
                @foreach($systemTemplates as $template)
                    <button type="button" class="quick-reply-item" data-quick-reply-insert="{{ $template->body }}"><strong>{{ $template->title }}</strong><span>{{ \Illuminate\Support\Str::limit($template->body, 60) }}</span></button>
                @endforeach
            </div>
            <div class="quick-replies-dialog-footer">
                <button type="button" class="button button-small" data-add-quick-reply>Add your own</button>
                <a href="{{ route('customer.settings') }}#quick-replies" class="button button-small button-outline">Manage templates</a>
            </div>
        </dialog>

        <dialog class="call-dialog" data-call-dialog>
            <div class="call-dialog-header">
                <h3>Video Call</h3>
                <button class="call-dialog-close" data-call-hangup><i data-lucide="phone-off" aria-hidden="true"></i> Hang up</button>
            </div>
            <div class="call-dialog-body">
                <div class="call-videos">
                    <div class="call-video call-video-local" data-call-video-local>
                        <video autoplay muted playsinline></video>
                        <div class="call-video-label">You</div>
                    </div>
                    <div class="call-video call-video-remote" data-call-video-remote>
                        <video autoplay playsinline></video>
                        <div class="call-video-label">{{ $otherUser->name }}</div>
                    </div>
                </div>
                <div class="call-status" data-call-status>Calling...</div>
                <div class="call-controls">
                    <button type="button" class="call-control" data-call-mute><i data-lucide="mic" aria-hidden="true"></i> Mute</button>
                    <button type="button" class="call-control" data-call-video-toggle><i data-lucide="video" aria-hidden="true"></i> Video</button>
                </div>
            </div>
        </dialog>

        <div class="call-notification" data-call-notification style="display:none">
            <div class="call-notification-body">
                <strong>Incoming call</strong> from {{ $otherUser->name }}
                <div class="call-notification-actions">
                    <button class="button button-small call-accept" data-call-accept>Accept</button>
                    <button class="button button-small button-outline call-reject" data-call-reject>Reject</button>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection