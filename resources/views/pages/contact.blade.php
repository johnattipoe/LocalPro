@extends('layouts.app')

@section('title', 'Contact LocalPro Support')

@section('content')
@include('components.navbar')
<main class="requests-page">
    <section class="request-form-card">
        <div class="eyebrow muted">LOCALPRO SUPPORT</div>
        <h1>Tell us what you need help with.</h1>
        <p class="request-intro">Send a question about your account, provider verification, a booking, or a payment. Include the details support will need to understand the situation, but never send passwords, card numbers, or identity document images through this form.</p>
        @if(session('status'))<div class="flash-note" role="status">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="flash-note" role="alert">Please check the highlighted fields and try again.</div>@endif
        <form method="POST" action="{{ route('contact.store') }}" class="service-form">
            @csrf
            <div class="form-row"><label for="name">Your name</label><input id="name" name="name" required maxlength="120" autocomplete="name" value="{{ old('name', auth()->user()->name ?? '') }}">@error('name')<small class="field-error">{{ $message }}</small>@enderror</div>
            <div class="form-row"><label for="email">Email address</label><input id="email" name="email" type="email" required maxlength="255" autocomplete="email" value="{{ old('email', auth()->user()->email ?? '') }}">@error('email')<small class="field-error">{{ $message }}</small>@enderror</div>
            <div class="form-row"><label for="topic">What is this about?</label><select id="topic" name="topic" required><option value="">Choose a topic</option>@foreach(['account'=>'Account access','provider_verification'=>'Provider verification','booking'=>'Booking or job issue','payment'=>'Payment','safety'=>'Safety concern','other'=>'Something else'] as $value=>$label)<option value="{{ $value }}" @selected(old('topic')===$value)>{{ $label }}</option>@endforeach</select>@error('topic')<small class="field-error">{{ $message }}</small>@enderror</div>
            @auth
                <div class="form-row"><label for="service_request_id">Related request number <span>(optional)</span></label><input id="service_request_id" name="service_request_id" type="number" min="1" value="{{ old('service_request_id') }}" placeholder="Add the request number if this is about a job">@error('service_request_id')<small class="field-error">{{ $message }}</small>@enderror</div>
            @endauth
            <div class="form-row"><label for="message">How can we help?</label><textarea id="message" name="message" rows="7" minlength="20" maxlength="5000" required placeholder="Describe what happened, what you have tried, and what outcome would help.">{{ old('message') }}</textarea><small>Please provide at least 20 characters so our team can understand your request.</small>@error('message')<small class="field-error">{{ $message }}</small>@enderror</div>
            <button class="button form-submit" type="submit">Send to support</button>
        </form>
        <p class="request-intro">A support case is saved for the LocalPro admin team. If you have an account, you can also continue job-specific conversations from your request page.</p>
    </section>
</main>
@include('components.footer')
@endsection
