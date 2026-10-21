@extends('layouts.app.app')

@section('title', 'Marketplace terms | LocalPro')

@section('content')
@include('components.navbar.navbar')
<main class="requests-page information-page legal-page"><section class="request-form-card information-card legal-card">
    <div class="eyebrow muted"><i data-lucide="file-text" aria-hidden="true"></i> MARKETPLACE TERMS - WORKING DRAFT</div>
    <h1>{{ __('ui.terms') }}</h1>
    <p class="request-intro">These working terms describe the LocalPro product flow and practical expectations for using the marketplace. They are not a final contract or legal advice. Before launch, the operator must confirm the contracting organization, local requirements, payment and refund rules, cancellation process, provider relationship, and dispute and appeal procedures.</p>
    <div class="legal-summary"><i data-lucide="info" aria-hidden="true"></i><p><strong>In short:</strong> LocalPro helps customers find professionals and communicate about a job. Customers and professionals should agree on scope, schedule, materials, and final price before work begins. Verification is a document review, not a guarantee of the work.</p></div>
    <nav class="legal-toc" aria-label="On this page">
        <a href="#agreement">Using these terms</a><a href="#marketplace">LocalPro's role</a><a href="#accounts">Accounts and conduct</a><a href="#providers">Provider profiles</a><a href="#requests">Requests and bookings</a><a href="#payments">Prices and payments</a><a href="#reviews">Reviews and content</a><a href="#safety">Safety and concerns</a><a href="#availability">Availability and changes</a><a href="#support">Support and complaints</a>
    </nav>
    <div class="legal-rule" aria-hidden="true"></div>
    <h2 id="agreement"><i data-lucide="file-text" aria-hidden="true"></i> Using these terms</h2>
    <p>By creating an account or using marketplace features, you agree to follow the terms that the operator publishes for the live service. This draft is provided to explain the current product and must be completed, dated, and reviewed before it is treated as binding terms. If you do not agree with the published terms, do not create or continue using an account.</p>

    <h2 id="marketplace"><i data-lucide="briefcase-business" aria-hidden="true"></i> LocalPro's role</h2>
    <p>LocalPro is designed to help customers discover local professionals, submit service requests, and communicate about potential work. The customer and professional are responsible for deciding whether to proceed and for agreeing the job details directly. The operator must confirm the marketplace's legal role and describe it accurately in the final terms.</p>
    <p>Unless the operator expressly states otherwise in the final terms, a listing or verification status should not be read as a promise that LocalPro employs, supervises, insures, or guarantees a professional or a particular job outcome.</p>

    <h2 id="accounts"><i data-lucide="user-round" aria-hidden="true"></i> Accounts and respectful conduct</h2>
    <p>Use accurate account information, keep your sign-in credentials private, and tell support if you believe someone else has accessed your account. Use the site for genuine service activity and communicate respectfully.</p>
    <ul class="legal-list">
        <li><i data-lucide="check" aria-hidden="true"></i><span>Do not impersonate another person, create misleading profiles, or submit documents that are not yours to provide.</span></li>
        <li><i data-lucide="check" aria-hidden="true"></i><span>Do not threaten, harass, discriminate against, or repeatedly contact another member after they ask you to stop.</span></li>
        <li><i data-lucide="check" aria-hidden="true"></i><span>Do not post unlawful, deceptive, abusive, or irrelevant material, or use the marketplace to facilitate fraud.</span></li>
        <li><i data-lucide="check" aria-hidden="true"></i><span>Do not attempt to access another account, interfere with site security, or misuse information obtained through a service request.</span></li>
    </ul>

    <h2 id="providers"><i data-lucide="shield-check" aria-hidden="true"></i> Provider profiles and verification</h2>
    <p>Professionals are responsible for keeping their profile, service descriptions, availability, town, and indicative prices accurate. Only list services you are prepared and permitted to offer, and respond to requests in a timely and professional way.</p>
    <p>When a profile is marked verified, it means an administrator has reviewed documents submitted for the marketplace's verification process. Verification does not certify skill, workmanship, identity beyond the checks actually performed, insurance, licenses, conduct, or future performance. Customers should review the available information and discuss job requirements directly before hiring.</p>

    <h2 id="requests"><i data-lucide="clipboard-list" aria-hidden="true"></i> Requests, bookings, and changes</h2>
    <p>A service request communicates what a customer is looking for. It is not a substitute for confirming that a professional is available or that both sides have agreed to do the work. Before work begins, discuss and confirm:</p>
    <ul class="legal-list">
        <li><i data-lucide="check" aria-hidden="true"></i><span>The work to be done, what is excluded, and any expected materials or preparation.</span></li>
        <li><i data-lucide="check" aria-hidden="true"></i><span>The location, access arrangements, appointment time, and how changes or delays will be handled.</span></li>
        <li><i data-lucide="check" aria-hidden="true"></i><span>The price, whether it is fixed or an estimate, and any extra charges that may apply.</span></li>
    </ul>
    <p>In the current workflow, a provider may accept or decline a pending request and mark an accepted request complete. A customer may cancel a request while it is pending or accepted. These status controls record the marketplace workflow and do not replace any cancellation agreement or legal rights that may apply. The operator must publish a clear policy for late changes, no-shows, and cancellations before launch.</p>

    <h2 id="payments"><i data-lucide="credit-card" aria-hidden="true"></i> Prices, checkout, and payment records</h2>
    <p>Prices shown on a profile or service listing may be indicative. Confirm the final scope and price with the professional before work starts. Online checkout is available only when the operator has configured it and the completed job has a final price recorded.</p>
    <p>When enabled, checkout is processed through Paystack and the current integration records the payment to the marketplace merchant account. It does not automatically split funds or pay a provider. Refunds, failed or disputed transactions, chargebacks, fees, provider settlement, and payment timing require separate operator rules; do not rely on this draft as a refund promise. Never send full card details through a LocalPro message or support form.</p>

    <h2 id="reviews"><i data-lucide="star" aria-hidden="true"></i> Reviews and submitted content</h2>
    <p>Reviews should be based on a real service experience, be honest and relevant, and avoid insults, threats, personal contact details, or sensitive information. Do not submit a review in exchange for an undisclosed benefit or to mislead other members. The product currently allows a customer to review a completed job once, and reviews may be shown publicly when published.</p>
    <p>Only submit text, images, or documents that you have the right to share. The operator should specify in the final terms how content is moderated, when it may be hidden, and what process is available to challenge a moderation decision.</p>

    <h2 id="safety"><i data-lucide="life-buoy" aria-hidden="true"></i> Safety and concerns</h2>
    <p>Use your judgment when arranging work. Confirm who is attending, agree on access and payment arrangements, and avoid sharing unnecessary personal or financial details. Keep relevant job conversations in your account where practical so there is a record to refer to.</p>
    <p>If there is an immediate danger, contact local emergency services first. For a marketplace concern, contact <a href="{{ route('contact') }}">LocalPro support</a> with the service request number and a clear description. Dispute reports require a signed-in customer or provider connected to the request; under the current workflow, they relate to accepted or completed requests.</p>

    <h2 id="availability"><i data-lucide="settings" aria-hidden="true"></i> Availability, moderation, and changes</h2>
    <p>LocalPro is currently focused on Ho and nearby Volta Region communities. Listings, categories, and features may change as the marketplace develops, and availability depends on professionals who have completed their profiles and are approved to appear.</p>
    <p>The operator may need to investigate reports, restrict access, or hide content to protect the service and its members. The final terms should explain the circumstances, notice, review, and appeal process for content removal or account restrictions. The operator should communicate material changes to the terms and state when updated terms take effect.</p>

    <h2 id="support"><i data-lucide="mail" aria-hidden="true"></i> Support and complaints</h2>
    <p>For account, booking, provider verification, payment, safety, or other marketplace questions, use the <a href="{{ route('contact') }}">LocalPro support form</a>. Include the related request number when relevant, but do not include passwords, complete payment card numbers, or unrelated sensitive information. Support can review a report and explain available next steps; this draft does not promise a particular resolution or response time.</p>

    <p class="legal-operator-note"><strong>Before launch:</strong> have the final terms reviewed for the relevant jurisdiction; identify the operating entity and effective date; confirm LocalPro's marketplace role; and publish specific provider eligibility, cancellations, disputes and appeals, payment, refund, provider settlement, content moderation, liability, account suspension, and complaint-response rules.</p>
</section></main>
@include('components.footer.footer')
@endsection
