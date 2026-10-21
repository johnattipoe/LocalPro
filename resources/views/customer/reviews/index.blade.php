@extends('layouts.customer-portal.customer-portal')
@section('title', 'Reviews')
@section('content')
<div class="cp-page-heading"><div><span class="cp-eyebrow">{{ __('ui.your_feedback') }}</span><h1>{{ __('ui.reviews') }}</h1><p>See the feedback you have shared with LocalPro professionals.</p></div></div>
<section class="cp-panel cp-list-panel">@forelse($reviews as $review)<article class="cp-review-row"><div class="cp-review-top"><strong>{{ $review->provider->user->name ?? 'Professional' }}</strong><span class="cp-rating">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }} <small>{{ $review->rating }}/5</small></span></div><small>{{ $review->booking->serviceRequest->category->name ?? 'Service' }} · {{ $review->created_at->format('M j, Y') }}</small><p>{{ $review->comment ?: 'You left a rating without a written comment.' }}</p></article>@empty<div class="cp-empty"><span>☆</span><strong>No reviews yet</strong><p>After a job is completed, you can leave a review from its request page.</p><a href="{{ route('customer.history') }}">View job history →</a></div>@endforelse</section><div class="cp-pagination">{{ $reviews->links() }}</div>
@endsection
