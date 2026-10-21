@extends('layouts.provider-portal.provider-portal')
@section('title', 'Add a service')
@section('content')
<a class="pp-back-link" href="{{ route('provider.services.index') }}">&larr; My services</a>
<div class="pp-page-heading"><div><span class="pp-eyebrow">YOUR OFFERINGS</span><h1>Add a service</h1><p>Tell customers what you offer. You can agree the final amount before work begins.</p></div></div>
<section class="pp-panel pp-settings-panel pp-service-form-panel"><form method="POST" action="{{ route('provider.services.store') }}">@csrf @include('provider.services._form._form', ['service' => null])<div class="pp-settings-actions"><a class="pp-view-button" href="{{ route('provider.services.index') }}">{{ __('ui.cancel') }}</a><button class="pp-accept-button" type="submit">Save service</button></div></form></section>
@endsection
