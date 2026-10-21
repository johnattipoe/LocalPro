@extends('layouts.provider-portal.provider-portal')
@section('title', 'Account settings')
@section('content')
<div class="pp-page-heading"><div><span class="pp-eyebrow">{{ __('ui.account_preferences') }}</span><h1>{{ __('ui.settings') }}</h1><p>Update your login details and password.</p></div></div>
@if($errors->any())<div class="pp-form-errors" role="alert"><strong>Please check your details.</strong><span>{{ $errors->first() }}</span></div>@endif
<section class="pp-panel pp-settings-panel">
    <form method="POST" action="{{ route('provider.settings.update') }}">
        @csrf @method('PUT')
        <div class="pp-settings-section"><h2>Account information</h2><p>Your name and email are used for account access and customer communication.</p>
            <div class="pp-settings-grid">
                <div class="form-row"><label for="account-name">Full name</label><input id="account-name" name="name" autocomplete="name" maxlength="120" required value="{{ old('name', $user->name) }}">@error('name')<small class="field-error">{{ $message }}</small>@enderror</div>
                <div class="form-row"><label for="account-email">Email address</label><input id="account-email" type="email" name="email" autocomplete="email" maxlength="255" required value="{{ old('email', $user->email) }}">@error('email')<small class="field-error">{{ $message }}</small>@enderror</div>
            </div>
        </div>
        <div class="pp-settings-section pp-password-section"><h2>Change password</h2><p>Leave these fields empty if you do not want to change your password.</p>
            <div class="pp-settings-grid">
                <div class="form-row"><label for="current-password">Current password</label><input id="current-password" type="password" name="current_password" autocomplete="current-password">@error('current_password')<small class="field-error">{{ $message }}</small>@enderror</div>
                <div class="form-row"><label for="new-password">New password</label><input id="new-password" type="password" name="new_password" autocomplete="new-password">@error('new_password')<small class="field-error">{{ $message }}</small>@enderror</div>
                <div class="form-row"><label for="new-password-confirmation">Confirm new password</label><input id="new-password-confirmation" type="password" name="new_password_confirmation" autocomplete="new-password"></div>
            </div>
        </div>
        <div class="pp-settings-actions"><button class="pp-accept-button" type="submit">Save account settings</button></div>
    </form>
</section>
@endsection
