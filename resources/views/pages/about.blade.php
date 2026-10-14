@extends('layouts.app')

@section('title', 'About LocalPro')

@section('content')
@include('components.navbar')
<main class="requests-page">
    <section class="request-form-card">
        <div class="eyebrow muted">BUILT FOR THE VOLTA REGION</div>
        <h1>Good local work should be easier to find.</h1>
        <p class="request-intro">LocalPro helps people in Ho and nearby communities connect with independent professionals for repairs, home services, personal care, and creative work. Search by service and town, compare the details each professional shares, and send a request when you are ready.</p>
        <h2>Trust should be clear</h2>
        <p>Approved profiles have completed LocalPro's provider verification review. Customer ratings describe previous customer experiences. These signals help you make an informed choice, but they are not a promise about the result of a future job. Discuss the work, price, materials, and timing directly with the professional before confirming.</p>
        <h2>Made to grow with local communities</h2>
        <p>LocalPro is starting in Ho and the Volta Region so customers and professionals can shape the service around local needs. We aim to make it easier for skilled people to present their work, build a record of completed jobs, and meet customers who need their help.</p>
        <div class="form-actions"><a class="button" href="{{ route('professionals.index') }}">Find a professional</a><a class="text-link" href="{{ route('providers.register') }}">Offer your services</a></div>
    </section>
</main>
@include('components.footer')
@endsection
