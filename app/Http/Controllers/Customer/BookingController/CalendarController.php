<?php

namespace App\Http\Controllers\Customer\BookingController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Booking;
use App\Services\CalendarService\CalendarService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct(private CalendarService $calendar) {}

    public function sync(Request $request, Booking $booking)
    {
        abort_unless((int) $booking->customer_id === (int) $request->user()->id, 404);

        $result = $this->calendar->syncBookingToCalendar($booking, $request->user());

        if ($result['success']) {
            return back()->with('status', 'Booking synced to your Google Calendar.');
        }

        return back()->withErrors(['calendar' => $result['error'] ?? 'Calendar sync failed.']);
    }

    public function unsync(Request $request, Booking $booking)
    {
        abort_unless((int) $booking->customer_id === (int) $request->user()->id, 404);

        $result = $this->calendar->unsyncBookingFromCalendar($booking, $request->user());

        if ($result['success']) {
            return back()->with('status', 'Calendar event removed.');
        }

        return back()->withErrors(['calendar' => $result['error'] ?? 'Failed to remove calendar event.']);
    }
}
