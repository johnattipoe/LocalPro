@extends('layouts.provider-portal.provider-portal')
@section('title', 'My services')
@section('content')
<div class="pp-page-heading"><div><span class="pp-eyebrow">YOUR OFFERINGS</span><h1>My services</h1><p>Describe the work you do and show customers clear, indicative pricing.</p></div><a class="pp-accept-button" href="{{ route('provider.services.create') }}">Add a service +</a></div>
<section class="pp-panel pp-service-management">
    @forelse($services as $service)
        <article class="pp-managed-service"><div class="pp-managed-service-copy"><strong>{{ $service->title }}</strong><span>{{ $service->category?->name ?? $profile->profession }} · @if($service->pricing_type === 'quote')Quote after discussing the job@elseif($service->pricing_type === 'hourly')GH₵ {{ number_format((float) $service->price, 2) }} / hour @else GH₵ {{ number_format((float) $service->price, 2) }} fixed price @endif</span><p>{{ $service->description ?: 'Add a description to explain what this service includes.' }}</p></div><span class="pp-status {{ $service->is_active ? 'pp-status-completed' : 'pp-status-cancelled' }}">{{ $service->is_active ? 'Active' : 'Hidden' }}</span><a class="pp-view-button" href="{{ route('provider.services.edit', $service) }}">{{ __('ui.edit') }}</a><form method="POST" action="{{ route('provider.services.destroy', $service) }}" onsubmit="return confirm('Remove this service from your profile?')">@csrf @method('DELETE')<button class="pp-decline-button" type="submit">Remove</button></form></article>
    @empty
        <div class="pp-empty"><strong>No services listed yet</strong><p>Add your services so customers can find and compare your offerings.</p><a href="{{ route('provider.services.create') }}">Create your first service</a></div>
    @endforelse
</section>
@endsection
