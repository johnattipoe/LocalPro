@extends('layouts.app.app')

@section('title', 'About LocalPro | Local services in the Volta Region')

@section('content')
@include('components.navbar.navbar')
<main class="requests-page information-page about-page">
    <section class="about-hero" aria-labelledby="about-title">
        <div class="about-hero-copy">
            <div class="about-kicker"><span></span> BUILT FOR THE VOLTA REGION</div>
            <h1 id="about-title">{{ __('ui.about_localpro') }}</h1>
            <p>LocalPro brings people who need a hand together with independent professionals who have the skills to help. Find services nearby, learn about the people offering them, and start a conversation with confidence.</p>
            <div class="about-hero-actions">
                <a class="button" href="{{ route('professionals.index') }}">Find a professional <i data-lucide="arrow-right" aria-hidden="true"></i></a>
                <a class="about-secondary-link" href="{{ route('providers.register') }}">I offer a service</a>
            </div>
            <div class="about-local-note"><i data-lucide="map-pin" aria-hidden="true"></i> Starting in Ho and nearby communities across the Volta Region</div>
        </div>
        <aside class="about-brand-card" aria-label="LocalPro, trusted local services">
            <div class="about-brand-glow"></div>
            <img src="{{ asset('logo/localpro-mark.png') }}" alt="" width="76" height="76">
            <strong>Local skills.<br>Closer connections.</strong>
            <span>Made with local communities in mind.</span>
            <div class="about-brand-line"><i></i><i></i><i></i><i></i><i></i></div>
        </aside>
    </section>

    <section class="about-intro-section" aria-labelledby="about-purpose-title">
        <div class="about-section-heading">
            <span class="about-section-label">WHY LOCALPRO EXISTS</span>
            <h2 id="about-purpose-title">Finding the right help should feel straightforward.</h2>
        </div>
        <p>From home repairs and personal care to creative work, finding a professional often starts with asking around. LocalPro gives customers another way to discover local services, compare the information available, and connect directly with the people doing the work.</p>
    </section>

    <section class="about-values-grid" aria-label="What LocalPro is built around">
        <article class="about-value-card">
            <span class="about-value-number">01</span>
            <div class="about-value-icon"><i data-lucide="search" aria-hidden="true"></i></div>
            <h3>Discover local talent</h3>
            <p>Browse service profiles and find professionals offering the kind of help you need in your area.</p>
        </article>
        <article class="about-value-card">
            <span class="about-value-number">02</span>
            <div class="about-value-icon"><i data-lucide="shield-check" aria-hidden="true"></i></div>
            <h3>Make informed choices</h3>
            <p>Review the profile details, service information, verification status, and customer feedback available before you reach out.</p>
        </article>
        <article class="about-value-card">
            <span class="about-value-number">03</span>
            <div class="about-value-icon"><i data-lucide="handshake" aria-hidden="true"></i></div>
            <h3>Build trust over time</h3>
            <p>Clear conversations and honest reviews help customers and professionals build better working relationships.</p>
        </article>
    </section>

    <section class="about-trust-panel" aria-labelledby="about-trust-title">
        <div class="about-trust-mark"><i data-lucide="shield-check" aria-hidden="true"></i></div>
        <div>
            <span class="about-section-label">CLARITY MATTERS</span>
            <h2 id="about-trust-title">Trust should be clear—not assumed.</h2>
            <p>Approved profiles have completed LocalPro's provider verification review. Customer ratings reflect previous customer experiences. These details can help you make an informed choice, but they are not a guarantee of a future job's outcome.</p>
            <p>Before confirming a job, discuss the scope of work, price, materials, timing, and any other important details directly with the professional.</p>
        </div>
    </section>

    <section class="about-community-section" aria-labelledby="about-community-title">
        <div class="about-community-map" aria-hidden="true">
            <span class="about-map-ring about-map-ring-one"></span>
            <span class="about-map-ring about-map-ring-two"></span>
            <span class="about-map-pin"><span>Ho</span></span>
            <span class="about-map-label about-map-label-one">People</span>
            <span class="about-map-label about-map-label-two">Skills</span>
            <span class="about-map-label about-map-label-three">Community</span>
        </div>
        <div class="about-community-copy">
            <span class="about-section-label">ROOTED IN OUR COMMUNITY</span>
            <h2 id="about-community-title">A local beginning, with room to grow.</h2>
            <p>LocalPro is starting in Ho and the Volta Region so customers and professionals can shape the service around local needs. We want skilled people to have a place to present their work, grow their reputation, and meet customers looking for their help.</p>
            <p>As the community grows, we’ll keep working toward a useful, welcoming place to find services and make local connections.</p>
        </div>
    </section>

    <section class="about-audience-section" aria-labelledby="about-audience-title">
        <div class="about-audience-heading">
            <span class="about-section-label">A MARKETPLACE FOR BOTH SIDES</span>
            <h2 id="about-audience-title">Good connections work both ways.</h2>
            <p>LocalPro gives customers and independent professionals tools to find one another and work through the details.</p>
        </div>
        <div class="about-audience-grid">
            <article class="about-audience-card about-audience-customer">
                <div class="about-audience-icon"><i data-lucide="search" aria-hidden="true"></i></div>
                <span class="about-audience-tag">FOR CUSTOMERS</span>
                <h3>Find the right person for the job.</h3>
                <p>Explore service profiles, look at the information each professional shares, and send a request when you are ready to discuss your job.</p>
                <ul>
                    <li>Search by service and location</li>
                    <li>Review provider profiles and customer feedback</li>
                    <li>Keep job details and updates together</li>
                </ul>
                <a href="{{ route('professionals.index') }}">Browse professionals <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </article>
            <article class="about-audience-card about-audience-provider">
                <div class="about-audience-icon"><i data-lucide="user-round" aria-hidden="true"></i></div>
                <span class="about-audience-tag">FOR PROFESSIONALS</span>
                <h3>Put your skills in front of local customers.</h3>
                <p>Create a profile that explains what you do, the areas you serve, and how customers can learn more about your services.</p>
                <ul>
                    <li>Describe your services and service area</li>
                    <li>Set your availability and manage requests</li>
                    <li>Build a profile with customer reviews over time</li>
                </ul>
                <a href="{{ route('providers.register') }}">Create a professional profile <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </article>
        </div>
    </section>

    <section class="about-principles-section" aria-labelledby="about-principles-title">
        <div class="about-principles-heading">
            <span class="about-section-label">HOW WE THINK ABOUT THE EXPERIENCE</span>
            <h2 id="about-principles-title">Useful, honest, and grounded in real conversations.</h2>
        </div>
        <div class="about-principles-list">
            <article><span>01</span><div><h3>Keep the details visible</h3><p>Profiles, requests, and messages help both sides return to the information they need while arranging a job.</p></div></article>
            <article><span>02</span><div><h3>Let people make informed decisions</h3><p>Show the available profile details and feedback clearly, while being honest about what verification and reviews can—and cannot—tell you.</p></div></article>
            <article><span>03</span><div><h3>Leave room for direct agreement</h3><p>Every job is different. Customers and professionals should discuss the work, timing, and final price before deciding to proceed.</p></div></article>
        </div>
    </section>

    <section class="about-faq-section" aria-labelledby="about-faq-title">
        <div class="about-faq-heading">
            <span class="about-section-label">A FEW HELPFUL DETAILS</span>
            <h2 id="about-faq-title">Questions about LocalPro?</h2>
            <p>Here are a few things worth knowing before you get started.</p>
        </div>
        <div class="about-faq-list">
            <details>
                <summary>What does a verified provider profile mean?</summary>
                <p>An approved provider profile has completed LocalPro's identity verification review. It is one useful signal when comparing profiles, but it is not a guarantee of work quality or a substitute for discussing the job directly.</p>
            </details>
            <details>
                <summary>How do I know what a job will cost?</summary>
                <p>Prices and quotes depend on the specific work. Review any pricing information on a professional's profile, then discuss the scope, materials, travel, and final price with them before confirming.</p>
            </details>
            <details>
                <summary>Does LocalPro process every payment?</summary>
                <p>Not necessarily. Online payments are available only when payment processing has been configured for the marketplace. Check the options shown for your request and agree on payment arrangements before work begins.</p>
            </details>
            <details>
                <summary>How can I get help with an account or request?</summary>
                <p>Visit the <a href="{{ route('contact') }}">contact support page</a> for account, verification, booking, payment, and other questions. For job-specific updates, return to the conversation on your request page.</p>
            </details>
        </div>
    </section>

    <section class="about-bottom-cta" aria-labelledby="about-cta-title">
        <div>
            <span class="about-section-label">YOUR NEXT STEP STARTS HERE</span>
            <h2 id="about-cta-title">Looking for help—or ready to offer yours?</h2>
            <p>Explore local professionals or create a profile and share your skills with the community.</p>
        </div>
        <div class="about-hero-actions">
            <a class="button" href="{{ route('professionals.index') }}">Explore professionals <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            <a class="about-cta-secondary" href="{{ route('providers.register') }}">Join as a professional</a>
        </div>
    </section>
</main>
@include('components.footer.footer')
@endsection
