@extends('layouts.admin.admin')

@section('title', 'Provider verification | LocalPro')

@section('content')
<div class="admin-page-content">
    <div class="requests-heading">
        <div>
            <div class="eyebrow muted">TRUST & SAFETY</div>
            <h1>Provider verification</h1>
            <p>Review provider profiles, experience, and supporting documents before making a decision.</p>
        </div>
        <a class="button" href="{{ route('admin.providers.index', ['status' => 'pending']) }}">Pending review</a>
    </div>

    @if(session('status'))
        <div class="flash-note">✓ {{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="flash-note admin-flash-error" role="alert">{{ $errors->first() }}</div>
    @endif

    <nav class="filter-tabs" aria-label="Filter providers by verification status">
        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All providers'] as $key => $label)
            <a class="{{ $status === $key ? 'selected' : '' }}" href="{{ route('admin.providers.index', ['status' => $key]) }}">{{ $label }}</a>
        @endforeach
    </nav>

    <div class="admin-provider-list admin-provider-review-list">
        @forelse($providers as $provider)
            <article class="admin-provider-card admin-provider-review-card">
                <div class="profile-avatar" aria-hidden="true">{{ mb_substr($provider->user->name, 0, 1) }}</div>
                <div class="admin-provider-info admin-provider-review-info">
                    <header class="admin-provider-review-heading">
                        <div>
                            <strong>{{ $provider->user->name }}</strong>
                            <span>{{ $provider->profession }} · {{ $provider->city }}, {{ $provider->region ?: 'Volta Region' }}</span>
                        </div>
                        <span class="status-pill status-{{ $provider->verification_status }}">{{ ucfirst($provider->verification_status) }}</span>
                    </header>

                    <section class="admin-provider-review-section" aria-label="Provider details">
                        <h2>Provider details</h2>
                        <div class="admin-provider-review-facts">
                            <p><strong>Email</strong><span>{{ $provider->user->email }}</span></p>
                            <p><strong>Phone</strong><span>{{ $provider->user->phone ?: 'Not provided' }}</span></p>
                            <p><strong>Years of experience</strong><span>{{ $provider->years_experience !== null ? $provider->years_experience : 'Not provided' }}</span></p>
                            <p><strong>Areas served</strong><span>{{ $provider->service_area ?: 'Not provided' }}</span></p>
                        </div>
                        <div class="admin-provider-review-description">
                            <h3>About their work</h3>
                            <p>{{ $provider->bio ?: 'No professional description provided.' }}</p>
                        </div>
                        <div class="admin-provider-review-description">
                            <h3>Experience and service history</h3>
                            <p>{{ $provider->experience_summary ?: 'No experience summary provided.' }}</p>
                        </div>
                    </section>

                    <section class="admin-provider-review-section" aria-label="Submitted verification documents">
                        <h2>Submitted documents <span>{{ $provider->verifications->count() }}</span></h2>
                        @if($provider->verifications->isNotEmpty())
                            <ul class="admin-provider-document-list">
                                @foreach($provider->verifications as $document)
                                    <li>
                                        <div>
                                            <strong>{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</strong>
                                            <small>Submitted {{ $document->created_at->format('M j, Y') }}</small>
                                        </div>
                                        <span class="status-pill status-{{ str_replace('_', '-', $document->status) }}">{{ ucfirst($document->status) }}</span>
                                        <a class="admin-provider-document-download" href="{{ route('admin.verification.download', $document) }}">Download</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="admin-provider-no-documents">No supporting documents submitted yet.</p>
                        @endif
                    </section>
                </div>

                @if(in_array($provider->verification_status, ['pending', 'rejected'], true))
                    <form method="POST" action="{{ route('admin.providers.update', $provider) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="verification_status" value="approved">
                        <button class="button" type="submit">{{ __('ui.approve') }}</button>
                    </form>
                @endif
                @if(in_array($provider->verification_status, ['pending', 'approved'], true))
                    <form method="POST" action="{{ route('admin.providers.update', $provider) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="verification_status" value="rejected">
                        <button class="button button-secondary" type="submit">Reject</button>
                    </form>
                @endif
            </article>
        @empty
            <section class="requests-empty">
                <span class="empty-icon">✓</span>
                <h2>No {{ $status === 'all' ? 'providers' : $status.' providers' }} to show</h2>
                <p>Provider accounts will appear here when they register.</p>
            </section>
        @endforelse
    </div>

    <div class="pagination">{{ $providers->links() }}</div>
</div>
@endsection
