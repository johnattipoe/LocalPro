<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ServiceRequest;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('service-request.{serviceRequestId}', function ($user, $serviceRequestId) {
    $serviceRequest = ServiceRequest::query()->with('provider')->find($serviceRequestId);

    if (! $serviceRequest || ! $user->is_active) {
        return false;
    }

    return (int) $serviceRequest->customer_id === (int) $user->id
        || (int) $serviceRequest->provider?->user_id === (int) $user->id;
});
