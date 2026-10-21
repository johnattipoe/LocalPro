@extends('layouts.app.app')

@section('title', 'Privacy information | LocalPro')

@section('content')
@include('components.navbar.navbar')
<main class="requests-page information-page legal-page"><section class="request-form-card information-card legal-card">
    <div class="eyebrow muted"><i data-lucide="shield-check" aria-hidden="true"></i> PRIVACY INFORMATION - WORKING DRAFT</div>
    <h1>{{ __('ui.privacy') }}</h1>
    <p class="request-intro">This draft explains the kinds of information the current LocalPro application may use and why. It is not a final privacy notice or legal advice. The operator must confirm the responsible organization, hosting and service providers, retention periods, user choices, and applicable legal requirements before public launch.</p>
    <div class="legal-summary"><i data-lucide="info" aria-hidden="true"></i><p><strong>In short:</strong> LocalPro uses account and job details to run the marketplace, helps customers and professionals communicate, and may show public provider profiles and published reviews. Keep passwords and payment card details out of messages and support requests.</p></div>
    <nav class="legal-toc" aria-label="On this page">
        <a href="#scope">About this notice</a><a href="#information">Information collected</a><a href="#use">How information is used</a><a href="#visibility">Profiles and visibility</a><a href="#sharing">Sharing and services</a><a href="#payments">{{ __('ui.payments') }}</a><a href="#security">Storage and security</a><a href="#retention">Retention and choices</a><a href="#children">Children</a><a href="#changes">Changes and contact</a>
    </nav>
    <div class="legal-rule" aria-hidden="true"></div>
    <h2 id="scope"><i data-lucide="file-text" aria-hidden="true"></i> About this notice</h2>
    <p>This notice concerns information handled through LocalPro's website and marketplace features. It describes the product as currently designed, not every future feature or the practices of independent professionals. A professional may separately handle information you share with them; discuss directly what they need and how they will use it.</p>

    <h2 id="information"><i data-lucide="database" aria-hidden="true"></i> Information collected</h2>
    <p>Depending on how you use the site, information may include:</p>
    <ul class="legal-list">
        <li><i data-lucide="user-round" aria-hidden="true"></i><span><strong>Account details:</strong> name, email address, optional phone number, account role, and town.</span></li>
        <li><i data-lucide="briefcase-business" aria-hidden="true"></i><span><strong>Provider profile details:</strong> profession, service descriptions, prices, availability, biography, location, and verification status.</span></li>
        <li><i data-lucide="clipboard-list" aria-hidden="true"></i><span><strong>Marketplace activity:</strong> service requests, booking details, messages, reviews, payment references and statuses, and support cases you submit.</span></li>
        <li><i data-lucide="shield-check" aria-hidden="true"></i><span><strong>Verification material:</strong> identity or business documents submitted by providers for administrator review.</span></li>
        <li><i data-lucide="settings" aria-hidden="true"></i><span><strong>Technical and session information:</strong> information needed to keep the site, account sessions, and security controls operating. The operator should document any analytics or optional cookies used in production.</span></li>
    </ul>
    <p>Only provide information relevant to the service. Do not send passwords, full payment card details, or unrelated sensitive information through messages or support forms.</p>

    <h2 id="use"><i data-lucide="workflow" aria-hidden="true"></i> How information is used</h2>
    <p>LocalPro may use information to create and manage accounts, show provider listings, match service requests with professionals, facilitate booking conversations, display reviews, review provider verification submissions, record payment status when checkout is enabled, respond to support cases, protect the marketplace, and maintain its technical operation.</p>
    <p>Information may also be used to investigate reports of misuse and to maintain records necessary to operate the service. The operator should specify any additional purposes before enabling them.</p>

    <h2 id="visibility"><i data-lucide="eye" aria-hidden="true"></i> Profiles and visibility</h2>
    <p>Provider profile information intended for a public listing, such as a name, profession, service description, town, indicative price, availability, verification status, and published customer reviews, may be visible to visitors. Do not include private contact details or sensitive personal information in public profile content unless you intend it to be public.</p>
    <p>Customers and the professional connected to a request can see information needed to discuss and arrange that job, including request details and related messages. Administrators with an operational need may access account, verification, support, and marketplace records.</p>

    <h2 id="sharing"><i data-lucide="share-2" aria-hidden="true"></i> Sharing and service providers</h2>
    <p>Information may be made available to the people involved in a service request, authorized LocalPro administrators, and third-party services needed to run enabled features. For example, Paystack is used for checkout when the operator has configured it. The operator should publish the current hosting, email, analytics, and other service providers and confirm their processing terms before launch.</p>
    <p>LocalPro should not ask you to send payment card numbers in a message or support ticket. Follow the payment provider's secure checkout flow when online payment is available.</p>

    <h2 id="payments"><i data-lucide="credit-card" aria-hidden="true"></i> Payments and transaction records</h2>
    <p>When online checkout is enabled, the payment provider processes the checkout. LocalPro records marketplace transaction information such as the booking, amount, currency, provider reference, and payment status so the customer can see whether a payment was confirmed and support staff can investigate a reported issue. Confirm the current payment-provider arrangement and data responsibilities with the operator.</p>

    <h2 id="security"><i data-lucide="lock-keyhole" aria-hidden="true"></i> Storage and security</h2>
    <p>Provider verification documents are designed to be stored on private application storage and served through an administrator-restricted route. Access to accounts and operational records should be limited to people who need it. No website or storage system can be promised completely secure; protect your password, sign out on shared devices, and tell support if you suspect unauthorized account use.</p>
    <p>Production hosting, encryption and backup configuration, administrator access, incident handling, and document storage controls must be reviewed and maintained by the operator.</p>

    <h2 id="retention"><i data-lucide="clock-3" aria-hidden="true"></i> Retention and your choices</h2>
    <p>Records may be kept for as long as needed to operate accounts, preserve request and payment history, handle support cases, maintain marketplace integrity, and meet applicable obligations. The current product draft does not define exact deletion or retention periods. Ask the operator about correcting account details, closing an account, or requesting access to information. Any request may need to be assessed against applicable requirements and records that must be retained.</p>

    <h2 id="children"><i data-lucide="users-round" aria-hidden="true"></i> Children and account eligibility</h2>
    <p>LocalPro is designed for people arranging or offering local services, not for children to create accounts independently. The operator should confirm and publish the minimum age and any consent requirements that apply before launch. If you believe a child has submitted personal information, contact support so the operator can review the situation.</p>

    <h2 id="changes"><i data-lucide="mail" aria-hidden="true"></i> Changes and questions</h2>
    <p>The operator should update this notice when the service, data practices, or applicable requirements change and provide an appropriate way to communicate material updates. For privacy questions or a request about your information, use the <a href="{{ route('contact') }}">LocalPro support form</a>. Do not include passwords or full payment card details in your message.</p>

    <p class="legal-operator-note"><strong>Before launch:</strong> replace this draft with a reviewed notice naming the responsible organization and contact, describing actual production hosting and processors, setting retention and deletion periods, explaining applicable user rights and choices, documenting cookie or analytics use, and confirming the age policy and incident process.</p>
</section></main>
@include('components.footer.footer')
@endsection
