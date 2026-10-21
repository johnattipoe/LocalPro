<?php

namespace App\Http\Controllers\Customer\BookingController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['serviceRequest.category', 'provider.user', 'payments'])
            ->where('customer_id', $request->user()->id)->latest()->paginate(10);

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
        ]);

        return view('customer.bookings.show', compact('booking'));
    }
}
