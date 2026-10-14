@extends('layouts.app')

@section('title', 'How LocalPro works')

@section('content')
@include('components.navbar')
<main class="requests-page">
    <section class="request-form-card">
        <div class="eyebrow muted">A CLEARER WAY TO GET HELP</div>
        <h1>From your first search to the finished job.</h1>
        <p class="request-intro">LocalPro keeps the steps and conversation together, so both sides can agree on what happens next.</p>
        <div class="how-steps">
            <article><b>01 - Search nearby</b><p>Choose the kind of help you need and the town where the work will happen. Compare available profiles, service details, indicative prices, customer reviews, and verification status.</p></article>
            <article><b>02 - Explain the job</b><p>Send a request with a useful description, your location, and a preferred time. Add practical details such as access instructions or what you have already tried, but do not include passwords or sensitive financial information.</p></article>
            <article><b>03 - Agree before work starts</b><p>The professional can respond, discuss the scope, suggest a quote, and confirm a schedule. Use the request conversation to clarify materials, travel costs, timing, and any changes before accepting the arrangement.</p></article>
            <article><b>04 - Track the work</b><p>Your request page shows whether the job is waiting, accepted, completed, or cancelled. You can return to the same conversation and booking details while the work is being arranged.</p></article>
            <article><b>05 - Close the loop</b><p>After the job is marked complete, leave an honest review based on your experience. Reviews help other people compare providers and help professionals build a record of their work.</p></article>
        </div>
        <p><strong>For professionals:</strong> create a profile, describe the services you offer, set your service area and availability, submit identity documents for review, and manage incoming requests from your provider dashboard.</p>
        <p><strong>For customers:</strong> LocalPro currently supports direct discussion of quotes and job details. Online payments are available only when the marketplace administrator has configured payment processing.</p>
        <a class="button" href="{{ route('professionals.index') }}">Browse professionals</a>
    </section>
</main>
@include('components.footer')
@endsection
