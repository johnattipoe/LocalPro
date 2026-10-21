<?php

namespace App\Services\BookingService;

use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\NotificationService\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function transitionByProvider(ServiceRequest $serviceRequest, User $provider, string $status, array $bookingFields = []): void
    {
        DB::transaction(function () use ($serviceRequest, $provider, $status, $bookingFields) {
            $lockedRequest = ServiceRequest::whereKey($serviceRequest->id)->lockForUpdate()->firstOrFail();
            $allowed = [
                'pending' => ['accepted', 'declined'],
                'accepted' => ['completed'],
            ];

            if (! in_array($status, $allowed[$lockedRequest->status] ?? [], true)) {
                throw ValidationException::withMessages(['status' => 'This request can no longer move to that status. Refresh the page and try again.']);
            }

            $booking = Booking::where('service_request_id', $lockedRequest->id)->lockForUpdate()->firstOrFail();
            $lockedRequest->update(['status' => $status]);
            $booking->update(array_merge($bookingFields, ['status' => $status]));

            $this->notifications->send(
                $lockedRequest->customer,
                'Request ' . $status,
                $provider->name . ' marked your request as ' . $status . '.'
            );
        });
    }

    public function updateAcceptedDetails(ServiceRequest $serviceRequest, User $provider, array $bookingFields): void
    {
        DB::transaction(function () use ($serviceRequest, $provider, $bookingFields) {
            $lockedRequest = ServiceRequest::whereKey($serviceRequest->id)->lockForUpdate()->firstOrFail();
            if ($lockedRequest->provider_profile_id !== $provider->providerProfile?->id || $lockedRequest->status !== 'accepted') {
                throw ValidationException::withMessages(['status' => 'Only accepted requests can have their quote or schedule updated.']);
            }

            $booking = Booking::where('service_request_id', $lockedRequest->id)->lockForUpdate()->firstOrFail();
            $booking->update($bookingFields);
            $lockedRequest->loadMissing('customer');
            $this->notifications->send(
                $lockedRequest->customer,
                'Booking details updated',
                $provider->name . ' updated the quote or schedule for your accepted request.'
            );
        });
    }

    public function cancelByCustomer(ServiceRequest $serviceRequest, User $customer): void
    {
        DB::transaction(function () use ($serviceRequest, $customer) {
            $lockedRequest = ServiceRequest::whereKey($serviceRequest->id)->lockForUpdate()->firstOrFail();
            if ($lockedRequest->customer_id !== $customer->id || ! in_array($lockedRequest->status, ['pending', 'accepted'], true)) {
                throw ValidationException::withMessages(['request' => 'This request can no longer be cancelled.']);
            }

            $booking = Booking::where('service_request_id', $lockedRequest->id)->lockForUpdate()->firstOrFail();
            $lockedRequest->update(['status' => 'cancelled']);
            $booking->update(['status' => 'cancelled']);

            $lockedRequest->loadMissing('provider.user');
            $this->notifications->send(
                $lockedRequest->provider->user,
                'Service request cancelled',
                $customer->name . ' cancelled their service request.'
            );
        });
    }
}
