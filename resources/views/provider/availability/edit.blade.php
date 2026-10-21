@extends('layouts.provider-portal.provider-portal')
@section('title', 'Availability')
@section('content')
<div class="pp-page-heading"><div><span class="pp-eyebrow">WORKING HOURS</span><h1>{{ __('ui.availability') }}</h1><p>Set the days and hours when customers can request appointments with you.</p></div></div>
@if($errors->any())<div class="pp-form-errors" role="alert"><strong>Please check your availability.</strong><span>{{ $errors->first() }}</span></div>@endif
<section class="pp-panel pp-settings-panel pp-availability-panel">
    <form method="POST" action="{{ route('provider.availability.update') }}">
        @csrf @method('PUT')
        <div class="pp-settings-section"><h2>New service requests</h2><p>Choose whether customers can contact you for new work.</p><label class="provider-accepting-toggle"><input type="hidden" name="accepting_requests" value="0"><input type="checkbox" name="accepting_requests" value="1" @checked(old('accepting_requests', $profile->accepting_requests ?? true))><span><strong>Accepting new requests</strong><small>Show an availability badge on your public profile and service cards.</small></span></label></div>
        <div class="pp-settings-section"><h2>Working days</h2><p>Select each day you are usually available. Leave all days unselected to mark your schedule as not set.</p>
            <fieldset class="availability-field"><legend>Days available</legend>
                @error('availability_days')<small class="field-error">{{ $message }}</small>@enderror
                <div class="availability-days">@foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)<label><input type="checkbox" name="availability_days[]" value="{{ $day }}" @checked(in_array($day, old('availability_days', $profile->availability['days'] ?? [])))>{{ $day }}</label>@endforeach</div>
            </fieldset>
        </div>
        <div class="pp-settings-section"><h2>Typical hours</h2><p>Set both times, or leave both blank if your hours vary.</p>
            <div class="availability-hours"><label>Start time<input type="time" name="availability_start" value="{{ old('availability_start', $profile->availability['start'] ?? '') }}"></label><label>End time<input type="time" name="availability_end" value="{{ old('availability_end', $profile->availability['end'] ?? '') }}"></label></div>
            @error('availability_start')<small class="field-error">{{ $message }}</small>@enderror @error('availability_end')<small class="field-error">{{ $message }}</small>@enderror
        </div>
        <div class="pp-settings-actions"><a class="pp-view-button" href="{{ route('provider.dashboard') }}">{{ __('ui.cancel') }}</a><button class="pp-accept-button" type="submit">Save availability</button></div>
    </form>
</section>
@endsection
