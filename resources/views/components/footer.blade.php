<footer class="site-footer">
    <div class="site-footer-main">
        <div class="site-footer-intro">
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark">&#10003;</span><span>local<span>pro</span><small>VOLTA REGION</small></span></a>
            <p>Find trusted local professionals, talk through the job, and agree on the details before work begins.</p>
            <span class="footer-location">Serving Ho and nearby towns in the Volta Region</span>
        </div>

        <nav class="site-footer-column" aria-label="Explore LocalPro">
            <h2>Explore</h2>
            <a href="{{ route('services.index') }}">Browse services</a>
            <a href="{{ route('professionals.index') }}">Find a professional</a>
            <a href="{{ route('how-it-works') }}">How it works</a>
            <a href="{{ route('about') }}">About LocalPro</a>
        </nav>

        <nav class="site-footer-column" aria-label="For LocalPro members">
            <h2>Get started</h2>
            <a href="{{ route('register') }}">Create a customer account</a>
            <a href="{{ route('providers.register') }}">Become a professional</a>
            <a href="{{ route('login') }}">Log in to LocalPro</a>
        </nav>

        <nav class="site-footer-column" aria-label="Help and policies">
            <h2>Help &amp; information</h2>
            <a href="{{ route('home') }}#answers">Frequently asked questions</a>
            <a href="{{ route('contact') }}">Contact support</a>
            <a href="{{ route('privacy') }}">Privacy information</a>
            <a href="{{ route('terms') }}">Marketplace terms</a>
        </nav>
    </div>

    <div class="site-footer-bottom">
        <small>&#169; {{ date('Y') }} LocalPro. Made for the Volta Region.</small>
        <a href="{{ route('home') }}#top">Back to top &#8593;</a>
    </div>
</footer>
