@extends('layouts.app.app')

@section('title', 'Contact LocalPro Support')

@section('content')
@include('components.navbar.navbar')
<main class="requests-page information-page contact-page">
    <header class="contact-page-heading">
        <div class="eyebrow muted">LOCALPRO SUPPORT</div>
        <h1>{{ __('ui.help_support') }}</h1>
        <p>Send our team a message about your account, a service request, provider verification, or another concern. Share enough detail for us to understand what happened, and we’ll follow up by email.</p>
    </header>

    <div class="contact-content-grid">
        <section class="request-form-card information-card contact-card" aria-labelledby="contact-form-title">
            <div class="contact-form-heading">
                <span class="contact-form-icon"><i data-lucide="mail" aria-hidden="true"></i></span>
                <div>
                    <span class="about-section-label">SEND A SUPPORT REQUEST</span>
                    <h2 id="contact-form-title">Write to our support team</h2>
                    <p>Fields marked with an asterisk are required.</p>
                </div>
            </div>
            @if(session('status'))<div class="flash-note information-success" role="status">{{ session('status') }}</div>@endif
            @if($errors->any())<div class="flash-note information-error" role="alert">Please check the highlighted fields and try again.</div>@endif
            <form method="POST" action="{{ route('contact.store') }}" class="service-form">
                @csrf
                <div class="form-row"><label for="name">Your name <span aria-hidden="true">*</span></label><input id="name" name="name" required maxlength="120" autocomplete="name" value="{{ old('name', auth()->user()->name ?? '') }}" @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror>@error('name')<small class="field-error" id="contact-name-error" role="alert">{{ $message }}</small>@enderror</div>
                <div class="form-row"><label for="email">Email address <span aria-hidden="true">*</span></label><input id="email" name="email" type="email" required maxlength="255" autocomplete="email" value="{{ old('email', auth()->user()->email ?? '') }}" @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror>@error('email')<small class="field-error" id="contact-email-error" role="alert">{{ $message }}</small>@enderror</div>
                <div class="form-row"><label for="topic">What is this about? <span aria-hidden="true">*</span></label><select id="topic" name="topic" required @error('topic') aria-invalid="true" aria-describedby="contact-topic-error" @enderror><option value="">Choose a topic</option>@foreach(['account'=>'Account access','provider_verification'=>'Provider verification','booking'=>'Booking or job issue','dispute'=>'Dispute or resolution request','payment'=>'Payment','safety'=>'Safety concern','other'=>'Something else'] as $value=>$label)<option value="{{ $value }}" @selected(old('topic', request('topic'))===$value)>{{ $label }}</option>@endforeach</select>@error('topic')<small class="field-error" id="contact-topic-error" role="alert">{{ $message }}</small>@enderror</div>
                @auth
                    <div class="form-row"><label for="service_request_id">Related request number <span>(optional)</span></label><input id="service_request_id" name="service_request_id" type="number" min="1" value="{{ old('service_request_id', request('service_request_id')) }}" placeholder="Add the request number if this is about a job" @error('service_request_id') aria-invalid="true" aria-describedby="contact-request-error" @enderror>@error('service_request_id')<small class="field-error" id="contact-request-error" role="alert">{{ $message }}</small>@enderror</div>
                @endauth
                <div class="form-row contact-message-row"><label for="message">How can we help? <span aria-hidden="true">*</span></label><textarea id="message" name="message" rows="7" minlength="20" maxlength="5000" required placeholder="Tell us what happened, when it happened, what you have tried, and what outcome would help." @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror>{{ old('message') }}</textarea><small>At least 20 characters, up to 5,000. Please leave out passwords and sensitive financial details.</small>@error('message')<small class="field-error" id="contact-message-error" role="alert">{{ $message }}</small>@enderror</div>
                <div class="contact-submit-row">
                    <button class="button form-submit" type="submit">Send support request <i data-lucide="arrow-right" aria-hidden="true"></i></button>
                    <small>Your message is saved as a support case for the LocalPro admin team.</small>
                </div>
            </form>
        </section>

        <aside class="contact-help-rail" aria-label="Contact form guidance">
            <section class="contact-help-card contact-help-primary">
                <span class="contact-help-label">HELP US HELP YOU</span>
                <h2>A few useful details make a difference.</h2>
                <p>Include a clear description and the steps you have already tried. If you are contacting us about a job, a request number can help us understand the context.</p>
                <ul>
                    <li>Choose the topic that best fits your question</li>
                    <li>Use the email address where you can receive a reply</li>
                    <li>For a job issue, include its request number when available</li>
                </ul>
            </section>
            <section class="contact-help-card contact-safety-card">
                <span class="contact-safety-icon"><i data-lucide="lock-keyhole" aria-hidden="true"></i></span>
                <div>
                    <h2>Keep sensitive information private</h2>
                    <p>Never send your password, card or bank details, one-time codes, or identity document images in a support message.</p>
                </div>
            </section>
            <section class="contact-help-card contact-dispute-card">
                <span class="contact-help-label">ABOUT DISPUTES</span>
                <h2>Reporting a booking dispute?</h2>
                <p>Please sign in with the customer or professional account connected to the request and include its request number. A dispute can be submitted after the provider accepts the request.</p>
            </section>
            <a class="contact-back-link" href="{{ route('how-it-works') }}">See how LocalPro works <i data-lucide="arrow-right" aria-hidden="true"></i></a>
        </aside>
    </div>

    <section class="contact-after-submit" aria-labelledby="contact-after-title">
        <span class="about-section-label">AFTER YOU SEND YOUR MESSAGE</span>
        <h2 id="contact-after-title">{{ __('ui.what_happens_next') }}</h2>
        <div class="contact-followup-grid">
            <article><span><i data-lucide="file-check" aria-hidden="true"></i></span><div><h3>Your case is recorded</h3><p>Your message is saved for the LocalPro admin team so it can be reviewed.</p></div></article>
            <article><span><i data-lucide="mail" aria-hidden="true"></i></span><div><h3>Look out for an email</h3><p>We’ll use the email address you provide to follow up about your support request.</p></div></article>
            <article><span><i data-lucide="message-circle" aria-hidden="true"></i></span><div><h3>Keep job conversations together</h3><p>For updates about a specific job, you can also return to the conversation on its request page.</p></div></article>
        </div>
    </section>
</main>
@include('components.footer.footer')
@endsection
