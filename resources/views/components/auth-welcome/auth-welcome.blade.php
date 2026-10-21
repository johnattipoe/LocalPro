<aside class="auth-welcome-panel">
    <a class="auth-brand" href="{{ route('home') }}" aria-label="LocalPro homepage">
        <img class="auth-brand-mark" src="{{ asset('logo/localpro-mark.png') }}" alt="">
        <span>Local<span>Pro</span><small>VOLTA REGION</small></span>
    </a>
    <div class="auth-welcome-copy">
        <span class="auth-welcome-kicker">{{ $kicker }}</span>
        <h2>{{ $headline }}</h2>
        <p>{{ $description }}</p>
        <ul>
            <li><span aria-hidden="true">&#10003;</span> Browse identity-verified professionals</li>
            <li><span aria-hidden="true">&#10003;</span> Compare local services and quotes</li>
            <li><span aria-hidden="true">&#10003;</span> Keep requests and messages together</li>
        </ul>
    </div>
    <span class="auth-welcome-location">Made for Ho and the Volta Region</span>
    <span class="auth-orb auth-orb-one" aria-hidden="true"></span>
    <span class="auth-orb auth-orb-two" aria-hidden="true"></span>
</aside>
