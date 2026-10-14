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
    <nav class="main-nav"><a href="{{ route('notifications.index') }}">Notifications</a><a class="active" href="{{ route('messages.index') }}">Messages</a></nav>
</header>
<main class="requests-page">
    <div class="requests-heading"><div><div class="eyebrow muted">YOUR CONVERSATIONS</div><h1>Messages</h1><p>Talk with customers or professionals about your service requests.</p></div></div>
    <div class="admin-provider-list">
        @forelse($messages as $message)
            @php($serviceRequest=$message->serviceRequest)
            @php($other=$serviceRequest->customer_id===auth()->id()?$serviceRequest->provider->user:$serviceRequest->customer)
            <a class="request-list-item" href="{{ route('messages.show',$serviceRequest) }}">
                <span class="request-list-icon">{{ mb_substr($other->name,0,1) }}</span>
                <span class="request-list-main"><strong>{{ $other->name }}</strong><small>{{ $serviceRequest->category->name ?? $serviceRequest->provider->profession }} &middot; {{ $serviceRequest->city }}</small><span>{{ \Illuminate\Support\Str::limit($message->body,100) }}</span></span>
                <span class="request-chevron">{{ $message->created_at->diffForHumans() }}</span>
            </a>
        @empty
            <section class="requests-empty"><h2>No conversations yet</h2><p>Messages are grouped with their service requests and appear here.</p></section>
        @endforelse
    </div>
    <div class="pagination">{{ $messages->links() }}</div>
</main>
</body>
</html>
