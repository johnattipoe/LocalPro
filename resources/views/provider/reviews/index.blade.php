@extends('layouts.provider-portal.provider-portal')
@section('title', 'Customer reviews')
@section('content')
<div class="pp-page-heading">
    <div><span class="pp-eyebrow">CUSTOMER FEEDBACK</span><h1>{{ __('ui.reviews') }}</h1><p>Published feedback from customers who have completed a job with you.</p></div>
    <div class="pp-rating-summary"><strong>★ {{ number_format((float) $profile->average_rating, 1) }}</strong><span>{{ number_format((int) $profile->review_count) }} reviews</span></div>
</div>
<section class="pp-panel pp-review-list">
    @forelse($reviews as $review)
        <article class="pp-review-item">
            <span class="pp-review-avatar">{{ collect(explode(' ', $review->customer?->name ?? 'C'))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span>
            <div class="pp-review-body">
                <div class="pp-review-head"><strong>{{ $review->customer?->name ?? 'Customer' }}</strong><span class="pp-review-stars">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }} <small>{{ $review->rating }}/5</small></span></div>
                <small>{{ $review->booking?->serviceRequest?->category?->name ?? $profile->profession }} · {{ $review->created_at->format('M j, Y') }}</small>
                <p>{{ $review->comment ?: 'This customer left a rating without a written comment.' }}</p>
            </div>
        </article>
    @empty
        <div class="pp-empty"><strong>No published reviews yet</strong><p>Customer reviews will appear here after completed work.</p><a href="{{ route('provider.bookings.index') }}">View bookings →</a></div>
    @endforelse
</section>
<div class="pp-pagination">{{ $reviews->links() }}</div>
@endsection
