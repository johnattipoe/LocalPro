<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My service requests | LocalPro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app.ts'])
</head>
<body>
<header class="topbar"><a class="brand" href="{{ route('home') }}"><span class="brand-mark">&#10003;</span><span>local<span>pro</span><small>VOLTA REGION</small></span></a><nav class="main-nav"><a href="{{ route('home') }}#services">Find services</a><a class="active" href="{{ route('customer.requests.index') }}">My requests</a></nav><div class="top-actions"><span class="location-chip">{{ auth()->user()->name }}</span><form method="POST" action="{{ route('logout') }}">@csrf<button class="login-link button-link">Log out</button></form></div></header>
<main class="requests-page">
    @if(session('status'))<div class="flash-note">&#10003; {{ session('status') }}</div>@endif
    <div class="requests-heading"><div><div class="eyebrow muted">YOUR LOCALPRO ACTIVITY</div><h1>My service requests</h1><p>Keep track of the jobs you have asked local professionals about.</p></div><a class="button" href="{{ route('home') }}#services">Find another pro <span>&rarr;</span></a></div>
    @if($requests->isNotEmpty())
        <div class="request-list">
            @foreach($requests as $serviceRequest)
                <a class="request-list-item" href="{{ route('customer.requests.show', $serviceRequest) }}">
                    <span class="request-list-icon">{{ $serviceRequest->category ? mb_substr($serviceRequest->category->name, 0, 1) : 'L' }}</span>
                    <span class="request-list-main"><strong>{{ $serviceRequest->category->name ?? $serviceRequest->provider->profession }}</strong><small>To {{ $serviceRequest->provider->user->name }} &middot; {{ $serviceRequest->city }} @if($serviceRequest->preferred_at)&middot; {{ $serviceRequest->preferred_at->format('M j, Y') }}@endif</small><span>{{ \Illuminate\Support\Str::limit($serviceRequest->description, 110) }}</span></span>
                    <span class="status-pill status-{{ $serviceRequest->status }}">{{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}</span>
                    <span class="request-chevron">&rarr;</span>
                </a>
            @endforeach
        </div>
        <div class="pagination">{{ $requests->links() }}</div>
    @else
        <section class="requests-empty"><span class="empty-icon">&#10003;</span><h2>Your requests will show up here.</h2><p>Find a verified professional and tell them what you need. You can follow each request from here.</p><a class="button" href="{{ route('home') }}#services">Explore local services <span>&rarr;</span></a></section>
    @endif
</main>
</body>
</html>
