@extends('layouts.app')

@section('title', 'Marketplace terms | LocalPro')

@section('content')
@include('components.navbar')
<main class="requests-page"><section class="request-form-card">
    <div class="eyebrow muted">MARKETPLACE TERMS - WORKING DRAFT</div>
    <h1>Clear expectations for using LocalPro.</h1>
    <p class="request-intro">These working terms explain the current product flow. They are not a final contract. The LocalPro operator should have the final terms reviewed and publish the business identity, support process, payment rules, cancellation policy, and dispute procedure before a public launch.</p>
    <h2>Using the marketplace</h2>
    <p>Keep account details accurate, protect your sign-in credentials, and use LocalPro to arrange genuine local services. Do not impersonate another person, misuse identity documents, harass other members, post unlawful material, or submit reviews for work that did not take place.</p>
    <h2>Customers and professionals</h2>
    <p>Customers and independent professionals are responsible for agreeing on the job scope, price, materials, access, schedule, and any changes before work begins. Provider verification confirms that an administrator reviewed submitted documents; it does not guarantee a professional's skill, conduct, insurance, or the outcome of a job.</p>
    <h2>Requests, reviews, and support</h2>
    <p>Request statuses help participants follow the job. A customer can cancel a request while it is pending or accepted; a provider can accept or decline a pending request and mark an accepted request complete. Reviews should describe a real experience and avoid private information. Contact support with the request number if something goes wrong; the operator must define a complete dispute and appeal process before launch.</p>
    <h2>Payments</h2>
    <p>Online checkout is available only when configured by the marketplace operator. The current integration records the customer payment to the marketplace merchant account. It does not automatically split funds, pay providers, issue refunds, or handle chargebacks. Do not use live checkout until the operator has published payment, refund, and provider settlement terms.</p>
    <h2>Marketplace availability</h2>
    <p>LocalPro may update service categories, suspend accounts, hide content, or limit access to protect members and operate the marketplace. The service is currently focused on Ho and nearby Volta Region communities; provider availability depends on approved profiles in each area.</p>
    <p><strong>Operator checklist:</strong> have local counsel review these terms, add an effective date and operating entity, and define cancellation, dispute, refund, liability, and account appeal procedures.</p>
</section></main>
@include('components.footer')
@endsection
