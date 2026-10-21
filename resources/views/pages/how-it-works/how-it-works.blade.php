@extends('layouts.app.app')

@section('title', 'How LocalPro Works | From search to service')

@section('content')
@include('components.navbar.navbar')
<main class="requests-page information-page how-page">
    <section class="how-hero" aria-labelledby="how-title">
        <div class="how-hero-copy">
            <div class="about-section-label"><span class="how-kicker-dot"></span> A CLEARER WAY TO GET HELP</div>
            <h1 id="how-title">{{ __('ui.how_it_works') }}</h1>
            <p>LocalPro helps customers and independent professionals find each other, discuss the details, and keep track of what happens next.</p>
            <div class="how-hero-actions">
                <a class="button" href="{{ route('professionals.index') }}">Find a professional <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                <a href="{{ route('providers.register') }}">I’m a professional</a>
            </div>
        </div>
        <div class="how-hero-visual" aria-hidden="true">
            <div class="how-visual-orbit how-visual-orbit-one"></div>
            <div class="how-visual-orbit how-visual-orbit-two"></div>
            <div class="how-visual-center"><img src="{{ asset('logo/localpro-mark.png') }}" alt=""></div>
            <span class="how-visual-chip how-chip-search">Find a service</span>
            <span class="how-visual-chip how-chip-chat">Start a conversation</span>
            <span class="how-visual-chip how-chip-done">Agree on the details</span>
        </div>
    </section>

    <section class="how-journey-section" aria-labelledby="how-journey-title">
        <div class="how-section-heading">
            <span class="about-section-label">THE CUSTOMER JOURNEY</span>
            <h2 id="how-journey-title">Five steps to a clearer arrangement.</h2>
            <p>Every job is different. Take time to talk through the details before deciding to go ahead.</p>
        </div>
        <div class="how-steps">
            <article>
                <span class="how-step-icon"><i data-lucide="search" aria-hidden="true"></i></span>
                <b>Search nearby</b>
                <p>Choose the service you need and where the work will happen. Browse profiles and compare service details, indicative prices, reviews, and verification status where available.</p>
                <small>Explore before you decide</small>
            </article>
            <article>
                <span class="how-step-icon"><i data-lucide="file-text" aria-hidden="true"></i></span>
                <b>Explain the job</b>
                <p>Send a request with a useful description, your location, and a preferred time. Mention relevant access or preparation details, but leave out passwords and sensitive financial information.</p>
                <small>Share the practical details</small>
            </article>
            <article>
                <span class="how-step-icon"><i data-lucide="message-circle" aria-hidden="true"></i></span>
                <b>Talk it through</b>
                <p>The professional can respond to the request. Discuss the scope, materials, travel costs, timing, quote, and any questions before accepting an arrangement.</p>
                <small>Agree on expectations</small>
            </article>
            <article>
                <span class="how-step-icon"><i data-lucide="clipboard-check" aria-hidden="true"></i></span>
                <b>Keep track</b>
                <p>Return to the request to check its status and follow the conversation as the work is arranged. The page shows whether a request is waiting, accepted, completed, or cancelled.</p>
                <small>Keep the conversation together</small>
            </article>
            <article>
                <span class="how-step-icon"><i data-lucide="star" aria-hidden="true"></i></span>
                <b>Close the loop</b>
                <p>After a job is marked complete, customers can leave an honest review of their experience. Reviews help others compare providers and let professionals build a record over time.</p>
                <small>Share a helpful review</small>
            </article>
        </div>
    </section>

    <section class="how-two-sided-section" aria-labelledby="how-two-sided-title">
        <div class="how-section-heading">
            <span class="about-section-label">A MARKETPLACE THAT WORKS BOTH WAYS</span>
            <h2 id="how-two-sided-title">Getting started looks a little different for everyone.</h2>
        </div>
        <div class="how-audience-grid">
            <article class="how-audience-card">
                <div class="how-audience-top"><span class="how-audience-symbol"><i data-lucide="search" aria-hidden="true"></i></span><span>FOR CUSTOMERS</span></div>
                <h3>Find help that fits your needs.</h3>
                <ol>
                    <li><span>1</span><p>Search for a service and look at professionals in your area.</p></li>
                    <li><span>2</span><p>Review profile details, feedback, and any listed pricing.</p></li>
                    <li><span>3</span><p>Send a request and explain what you need done.</p></li>
                    <li><span>4</span><p>Discuss the details and agree on the job before work begins.</p></li>
                </ol>
                <a class="button" href="{{ route('professionals.index') }}">Browse professionals <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </article>
            <article class="how-audience-card how-provider-card">
                <div class="how-audience-top"><span class="how-audience-symbol"><i data-lucide="user-round" aria-hidden="true"></i></span><span>FOR PROFESSIONALS</span></div>
                <h3>Help local customers discover your skills.</h3>
                <ol>
                    <li><span>1</span><p>Create an account and set up your professional profile.</p></li>
                    <li><span>2</span><p>Describe your services, service area, and availability.</p></li>
                    <li><span>3</span><p>Submit identity documents for LocalPro’s verification review.</p></li>
                    <li><span>4</span><p>Review incoming requests and discuss the job with customers.</p></li>
                </ol>
                <a class="button" href="{{ route('providers.register') }}">Create your profile <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </article>
        </div>
    </section>

    <section class="how-agreement-panel" aria-labelledby="how-agreement-title">
        <div class="how-agreement-mark"><i data-lucide="check" aria-hidden="true"></i></div>
        <div>
            <span class="about-section-label">BEFORE WORK BEGINS</span>
            <h2 id="how-agreement-title">Make sure the important details are clear.</h2>
            <p>Use the conversation to confirm what is included, the expected timing, the total price or quote, materials, travel costs, and how changes will be handled. If something is unclear, ask before you confirm.</p>
            <p>LocalPro supports direct discussion of quotes and job details. Online payments are available only when the marketplace administrator has configured payment processing. Check the options shown for your request and agree on payment arrangements with the professional.</p>
        </div>
    </section>

    <section class="how-safety-section" aria-labelledby="how-safety-title">
        <div class="how-safety-heading">
            <span class="about-section-label">THOUGHTFUL FROM THE START</span>
            <h2 id="how-safety-title">A few good habits for every request.</h2>
        </div>
        <div class="how-safety-grid">
            <article><span><i data-lucide="map-pin" aria-hidden="true"></i></span><h3>Describe the work clearly</h3><p>Include the issue, location, and timing so the professional can understand the request.</p></article>
            <article><span><i data-lucide="shield-check" aria-hidden="true"></i></span><h3>Check the details</h3><p>Review the profile and ask questions about anything you need clarified before confirming.</p></article>
            <article><span><i data-lucide="lock-keyhole" aria-hidden="true"></i></span><h3>Protect private information</h3><p>Do not share account passwords, one-time codes, or sensitive financial details in messages.</p></article>
        </div>
    </section>

    <section class="how-help-strip" aria-label="More information">
        <div><span class="about-section-label">NEED A HAND?</span><h2>We’re here to point you in the right direction.</h2><p>For account, verification, booking, or payment questions, contact LocalPro Support.</p></div>
        <a class="button" href="{{ route('contact') }}">Contact support <i data-lucide="arrow-right" aria-hidden="true"></i></a>
    </section>
</main>
@include('components.footer.footer')
@endsection
