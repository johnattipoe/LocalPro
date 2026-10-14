<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Messages | LocalPro</title>
    @vite(['resources/css/app.css','resources/ts/app.ts'])
</head>
<body>
<header class="topbar">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark">&#10003;</span><span>local<span>pro</span><small>VOLTA REGION</small></span></a>
    <nav class="main-nav"><a href="{{ auth()->user()->role==='provider' ? route('provider.bookings.index') : route('customer.requests.index') }}">Back to requests</a></nav>
    <div class="top-actions"><span class="location-chip">{{ auth()->user()->name }}</span><form method="POST" action="{{ route('logout') }}">@csrf<button class="login-link button-link">Log out</button></form></div>
</header>
<main class="requests-page">
    <a class="text-link" href="{{ auth()->user()->role==='provider' ? route('provider.bookings.show',$serviceRequest) : route('customer.requests.show',$serviceRequest) }}">&#8592; Back to request</a>
    @if(session('status'))<div class="flash-note">&#10003; {{ session('status') }}</div>@endif
    <div class="message-heading">
        <div class="profile-avatar">{{ mb_substr($otherUser->name,0,1) }}</div>
        <div><div class="eyebrow muted">REQUEST #{{ str_pad($serviceRequest->id,5,'0',STR_PAD_LEFT) }}</div><h1>{{ $otherUser->name }}</h1><p>{{ $serviceRequest->category->name ?? $serviceRequest->provider->profession }} &middot; {{ $serviceRequest->city }}</p></div>
        <span class="status-pill status-{{ $serviceRequest->status }}">{{ ucfirst($serviceRequest->status) }}</span>
    </div>
    <section class="message-panel">
        <div class="message-history" data-message-history data-poll-url="{{ route('messages.updates',$serviceRequest) }}" data-current-user="{{ auth()->id() }}" aria-live="polite" aria-relevant="additions text">
            @forelse($messages as $message)
                <article class="message-bubble {{ $message->sender_id===auth()->id()?'message-mine':'message-theirs' }}" data-message-id="{{ $message->id }}" data-sender-id="{{ $message->sender_id }}">
                    <small>{{ $message->sender_id===auth()->id()?'You':$otherUser->name }} &middot; {{ $message->created_at->format('M j, g:i A') }}</small><p>{{ $message->body }}</p>
                </article>
            @empty
                <div class="message-empty" data-message-empty><strong>Start the conversation</strong><p>Discuss the job details, quote, and timing here.</p></div>
            @endforelse
        </div>
        <p class="message-connection" data-message-connection role="status">Messages refresh while this page is open.</p>
        <form class="message-compose" method="POST" action="{{ route('messages.store',$serviceRequest) }}">@csrf
            <label for="message-body">Message {{ $otherUser->name }}</label>
            <textarea id="message-body" name="body" rows="3" maxlength="4000" required placeholder="Write a message about this request...">{{ old('body') }}</textarea>
            @error('body')<small class="field-error">{{ $message }}</small>@enderror
            <button class="button" type="submit">Send message <span>&#8594;</span></button>
        </form>
    </section>
</main>
</body>
</html>
