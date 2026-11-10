<?php

namespace App\Services\CalendarService;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CalendarService
{
    public function syncBookingToCalendar(Booking $booking, User $user): array
    {
        $accessToken = $user->token('google')?->access_token ?? session('google_access_token');

        if (! $accessToken) {
            return ['success' => false, 'error' => 'Google account not connected. Please sign in with Google first.'];
        }

        try {
            $calendarId = $booking->external_calendar_id ?? 'primary';

            $eventData = [
                'summary' => $booking->serviceRequest?->category?->name ?? 'Service booking',
                'description' => 'Booking with '.($booking->provider?->user?->name ?? 'Professional')."\n\nLocation: ".($booking->serviceRequest?->city ?? 'TBD'),
                'start' => [
                    'dateTime' => $booking->scheduled_at?->toISOString(),
                    'timeZone' => 'Africa/Accra',
                ],
                'end' => [
                    'dateTime' => $booking->scheduled_at?->copy()->addHour()->toISOString(),
                    'timeZone' => 'Africa/Accra',
                ],
            ];

            $response = Http::withToken($accessToken)
                ->post("https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events", $eventData);

            if (! $response->successful()) {
                return ['success' => false, 'error' => 'Failed to create calendar event: '.$response->body()];
            }

            $data = $response->json();
            $booking->update([
                'calendar_synced' => true,
                'external_calendar_id' => $data['id'] ?? null,
            ]);

            return ['success' => true, 'event_id' => $data['id'] ?? null];
        } catch (\Throwable $e) {
            Log::error('Calendar sync failed: '.$e->getMessage());

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function unsyncBookingFromCalendar(Booking $booking, User $user): array
    {
        $accessToken = $user->token('google')?->access_token ?? session('google_access_token');

        if (! $accessToken || ! $booking->external_calendar_id) {
            return ['success' => false, 'error' => 'No calendar event to remove.'];
        }

        try {
            $calendarId = 'primary';
            $eventId = $booking->external_calendar_id;

            $response = Http::withToken($accessToken)
                ->delete("https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events/{$eventId}");

            if (! $response->successful() && $response->status() !== 404) {
                return ['success' => false, 'error' => 'Failed to remove calendar event.'];
            }

            $booking->update([
                'calendar_synced' => false,
                'external_calendar_id' => null,
            ]);

            return ['success' => true];
        } catch (\Throwable $e) {
            Log::error('Calendar unsync failed: '.$e->getMessage());

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
