<?php

namespace App\Http\Controllers\Customer\BookingController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Booking;
use App\Services\BookingService\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookings) {}

    public function index(Request $request)
    {
        $bookings = Booking::with(['serviceRequest.category', 'provider.user', 'payments', 'occurrences'])
            ->where('customer_id', $request->user()->id)
            ->whereNull('parent_booking_id')
            ->latest()->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function show(Request $request, Booking $booking)
    {
        abort_unless((int) $booking->customer_id === (int) $request->user()->id, 404);
        $booking->load([
            'serviceRequest.category',
            'serviceRequest.provider.user',
            'provider.user',
            'payments',
            'review',
            'occurrences',
            'parent',
        ]);

        return view('customer.bookings.show', compact('booking'));
    }

    public function setRecurrence(Request $request, Booking $booking)
    {
        abort_unless((int) $booking->customer_id === (int) $request->user()->id, 404);

        $data = $request->validate([
            'recurrence_pattern' => ['required', Rule::in(['none', 'weekly', 'monthly'])],
            'occurrence_count' => ['nullable', 'integer', 'min:1', 'max:52'],
        ]);

        $pattern = $data['recurrence_pattern'];
        $count = $pattern === 'none' ? 1 : ($data['occurrence_count'] ?? 4);

        $this->bookings->createRecurringBooking($booking, $pattern, $count);

        return back()->with('status', 'Recurring schedule updated.');
    }
}
