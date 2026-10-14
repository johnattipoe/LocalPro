@extends('layouts.app')

@section('title', 'Privacy information | LocalPro')

@section('content')
@include('components.navbar')
<main class="requests-page"><section class="request-form-card">
    <div class="eyebrow muted">PRIVACY INFORMATION - WORKING DRAFT</div>
    <h1>How LocalPro handles information.</h1>
    <p class="request-intro">This page describes the information the current LocalPro application is designed to use. It is a product draft, not a final privacy notice. Before inviting the public, the LocalPro operator should confirm the responsible business contact, retention periods, hosting location, processors, and final privacy wording.</p>
    <h2>Information you provide</h2>
    <p>LocalPro may store your name, email address, optional phone number, town, provider profile details, service requests, messages, reviews, and support case details. Providers may submit identity or business documents for verification. Do not put passwords, payment card details, or unrelated sensitive information in messages or support requests.</p>
    <h2>How information is used</h2>
    <p>Account and profile information is used to operate the marketplace, show provider listings, route service requests, let participants communicate, manage provider verification, record reviews, prevent abuse, process support cases, and maintain payment records when online checkout is enabled.</p>
    <h2>Who can see it</h2>
    <p>Public provider profile details and published reviews may be visible to visitors. Customers and their selected providers can see the details and messages associated with their service request. Authorized administrators can access verification documents and support cases for marketplace operations. Payment processing is handled by Paystack when enabled; LocalPro records transaction references and payment status rather than card numbers.</p>
    <h2>Storage, access, and questions</h2>
    <p>Provider documents are stored on the application's private storage disk and are served through an administrator-only route. Production hosting, backups, document retention, and deletion procedures must be configured by the operator. To ask about your information or request help, use the <a href="{{ route('contact') }}">LocalPro support form</a>.</p>
    <p><strong>Operator checklist:</strong> replace this draft with a reviewed notice that names the operating organization, sets retention and deletion periods, explains applicable user choices, and identifies production hosting and service providers.</p>
</section></main>
@include('components.footer')
@endsection
