@extends('layouts.customer-portal.customer-portal')
@section('title', 'Leave a review')
@section('content')
<div class="cp-page-heading"><div><span class="cp-eyebrow">{{ __('ui.share_experience') }}</span><h1>{{ __('ui.how_did_it_go') }}</h1><p>Your feedback helps people in your community choose a professional.</p></div><a class="cp-button" href="{{ route('customer.requests.show', $serviceRequest) }}">Back to request</a></div>
<section class="cp-panel cp-form-panel"><form method="POST" action="{{ route('customer.reviews.store',$serviceRequest) }}" class="cp-profile-form cp-review-form">
    @csrf
    <div class="cp-review-context"><span aria-hidden="true">&#9733;</span><div><strong>{{ $serviceRequest->category->name ?? $serviceRequest->provider->profession }}</strong><small>With {{ $serviceRequest->provider->user->name }}</small></div></div>
    <label for="review-rating">Your rating<select id="review-rating" name="rating" required><option value="">Choose a rating</option>@foreach([5=>'5 - Excellent',4=>'4 - Good',3=>'3 - Okay',2=>'2 - Poor',1=>'1 - Very poor'] as $value=>$label)<option value="{{ $value }}" @selected(old('rating')==$value)>{{ $label }}</option>@endforeach</select>@error('rating')<small class="field-error" role="alert">{{ $message }}</small>@enderror</label>
    <label class="cp-review-comment" for="review-comment">Comment (optional)<textarea id="review-comment" name="comment" rows="5" maxlength="2000" placeholder="What should others know about your experience?">{{ old('comment') }}</textarea>@error('comment')<small class="field-error" role="alert">{{ $message }}</small>@enderror</label>
    <button class="cp-button" type="submit">Submit review</button>
</form></section>
@endsection

