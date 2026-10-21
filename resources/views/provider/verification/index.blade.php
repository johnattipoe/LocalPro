@extends('layouts.provider-portal.provider-portal')
@section('title', 'Identity verification')
@section('content')
<div class="pp-page-heading"><div><span class="pp-eyebrow">{{ __('ui.verification') }}</span><h1>{{ __('ui.verification') }}</h1><p>Submit documents so customers know your provider account has been reviewed.</p></div><span class="pp-status pp-status-{{ $profile->verification_status }}">{{ ucfirst($profile->verification_status) }}</span></div>
@php($hasSubmittedDocuments = $documents->isNotEmpty())
<ol class="pp-verification-steps" aria-label="Provider verification steps">
    <li class="is-complete"><span>1</span><div><strong>Profile created</strong><small>Your provider profile is ready.</small></div></li>
    <li class="{{ $hasSubmittedDocuments ? 'is-complete' : 'is-current' }}"><span>2</span><div><strong>Submit documents</strong><small>Upload an accepted identity or business document.</small></div></li>
    <li class="{{ $hasSubmittedDocuments ? 'is-current' : '' }} {{ $profile->verification_status === 'approved' ? 'is-complete' : '' }}"><span>3</span><div><strong>LocalPro review</strong><small>{{ $profile->verification_status === 'approved' ? 'Your provider profile is approved.' : 'We will update your status after review.' }}</small></div></li>
</ol>
<div class="pp-form-errors pp-verification-private"><strong>Your documents are private.</strong><span>Only authorized LocalPro administrators can access the files you submit.</span></div>
@if($errors->any())<div class="pp-form-errors" role="alert"><strong>Document was not submitted.</strong><span>{{ $errors->first() }}</span></div>@endif
<section class="pp-panel pp-settings-panel pp-verification-form"><h2>Submit verification documents</h2><p>Accepted formats: JPG, PNG, or PDF. Maximum file size: 5 MB.</p><form method="POST" enctype="multipart/form-data" action="{{ route('provider.verification.store') }}">@csrf
    <div class="pp-settings-grid"><div class="form-row"><label for="document-type">Document type</label><select id="document-type" name="document_type" required><option value="ghana_card" @selected(old('document_type') === 'ghana_card')>Ghana Card</option><option value="passport" @selected(old('document_type') === 'passport')>Passport</option><option value="drivers_license" @selected(old('document_type') === 'drivers_license')>Driver's licence</option><option value="business_document" @selected(old('document_type') === 'business_document')>Business document</option></select>@error('document_type')<small class="field-error">{{ $message }}</small>@enderror</div>
    <div class="form-row"><label for="verification-document">Choose file</label><input id="verification-document" type="file" name="document" accept=".jpg,.jpeg,.png,.pdf" required>@error('document')<small class="field-error">{{ $message }}</small>@enderror</div></div>
    <button class="pp-accept-button" type="submit">Submit for review</button>
</form></section>
<section class="pp-panel pp-verification-history"><div class="pp-section-title"><div><h2>Submitted documents</h2><p>Review status and administrator feedback for each submission.</p></div></div>
    @forelse($documents as $document)<article class="pp-managed-service"><div class="pp-managed-service-copy"><strong>{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</strong><span>Submitted {{ $document->created_at->format('M j, Y') }}</span>@if($document->review_notes)<p>{{ $document->review_notes }}</p>@endif</div><span class="pp-status pp-status-{{ str_replace('_', '-', $document->status) }}">{{ ucfirst($document->status) }}</span></article>@empty<div class="pp-empty"><strong>No documents submitted yet</strong><p>Submit an accepted identity or business document to begin verification.</p></div>@endforelse
</section>
@endsection
