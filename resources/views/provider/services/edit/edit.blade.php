@extends('layouts.provider-portal.provider-portal')
@section('title', 'Edit service')
@section('content')
<a class="pp-back-link" href="{{ route('provider.services.index') }}">&larr; My services</a>
<div class="pp-page-heading"><div><span class="pp-eyebrow">YOUR OFFERINGS</span><h1>Edit service</h1><p>Update your description, category, visibility, and pricing.</p></div></div>
<section class="pp-panel pp-settings-panel pp-service-form-panel"><form method="POST" action="{{ route('provider.services.update', $service) }}">@csrf @method('PUT') @include('provider.services._form._form', ['service' => $service])<div class="pp-settings-actions"><a class="pp-view-button" href="{{ route('provider.services.index') }}">{{ __('ui.cancel') }}</a><button class="pp-accept-button" type="submit">{{ __('ui.save_changes') }}</button></div></form></section>
@endsection
